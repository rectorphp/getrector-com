<?php

declare(strict_types=1);

namespace Rector\Website\Process;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class RectorProcessRunner
{
    /**
     * @var string
     */
    private $demoDir;


    public function __construct(string $demoDir)
    {
        $this->demoDir = $demoDir;
    }

    /**
     * @return mixed[]
     */
    public function run(RectorRun $rectorRun): array
    {
        $tempFile = $this->createTempPhpFile($rectorRun->getContentHash(), $rectorRun->getContent());
        $process = $this->createProcess($tempFile, $rectorRun->getSetName());
        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        // log it!
        $output = $process->getOutput();

        return Json::decode($output, Json::FORCE_ARRAY);
    }

    private function createTempPhpFile(string $contentHash, string $fileContent): string
    {
        $tempFile = $contentHash . '/rector_analyzed_file.php';

        FileSystem::write($this->demoDir . '/' . $tempFile, $fileContent);

        return $tempFile;
    }

    private function createProcess(string $filePath, string $setName): Process
    {
        // docker run --rm -v rector-demo:/project:ro rector/rector process /project/DemoFile.php --dry-run --set dead-code
        return new Process([
            'docker', 'run', '--rm',
            '-v', 'rector-demo:/project:ro',
            'process', '/project/' . $filePath,
            '--dry-run',
            '--output-format', 'json',
            '--set', $setName,
        ]);
    }
}
