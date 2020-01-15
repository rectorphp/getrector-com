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
        $this->demoDir = '/var/www/getrector.org/var/demo';
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
        $output = $this->getProcessOutput('ed5aa7322d451877135286912bfddc084fe288850e30268ed907ff522205eccd');

        $this->removeContainer('ed5aa7322d451877135286912bfddc084fe288850e30268ed907ff522205eccd');

        return Json::decode($output, Json::FORCE_ARRAY);
    }

    private function createTempPhpFile(string $contentHash, string $fileContent): string
    {
        $tempFile = $contentHash . '/rector_analyzed_file.php';

        FileSystem::createDir($this->demoDir . '/' . $contentHash);
        FileSystem::write($this->demoDir . '/' . $tempFile, $fileContent);

        return $tempFile;
    }

    private function createProcess(string $filePath, string $setName): Process
    {
        // docker run -i --rm -v rector-demo:/project:ro rector/rector process /project/DemoFile.php --dry-run --set dead-code > output.txt
        return new Process([
            'docker', 'run', '-i',
            '--name', 'ed5aa7322d451877135286912bfddc084fe288850e30268ed907ff522205eccd',
            '-v', '/Users/janmikes/Sites/getrector.org/var/demo:/project:ro',
            'rector/rector',
            'process', '/project/' . $filePath,
            '--dry-run',
            '--output-format', 'json',
            '--set', $setName,
        ]);
    }

    private function getProcessOutput(string $identifier): string
    {
        $process = new Process([
            'docker', 'logs', $identifier
        ]);

        $process->run();

        return $process->getOutput();
    }

    private function removeContainer(string $identifier): void
    {
        $process = new Process([
            'docker', 'rm', $identifier
        ]);

        $process->run();
    }
}
