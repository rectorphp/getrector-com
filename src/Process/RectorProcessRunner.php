<?php

declare(strict_types=1);

namespace Rector\Website\Process;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Exception\ShouldNotHappenException;
use Rector\Website\ValueObject\RectorRunValueObject;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class RectorProcessRunner
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @return mixed[]
     */
    public function run(RectorRunValueObject $rectorRunValueObject): array
    {
        $tempFile = $this->createTempPhpFile($rectorRunValueObject);
        $process = $this->createProcess($rectorRunValueObject, $tempFile);
        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        // log it!
        $output = $process->getOutput();

        return Json::decode($output, Json::FORCE_ARRAY);
    }

    private function createTempPhpFile(RectorRunValueObject $rectorRunValueObject): string
    {
        $tempFile = sys_get_temp_dir() . '/_rector_temp_analyzed_file.php';
        if ($rectorRunValueObject->getContent() === null) {
            throw new ShouldNotHappenException();
        }

        FileSystem::write($tempFile, $rectorRunValueObject->getContent());

        return $tempFile;
    }

    private function createProcess(RectorRunValueObject $rectorRunValueObject, string $tempPhpFile): Process
    {
        return new Process([
            'vendor/bin/rector',
            'process', $tempPhpFile,
            '--dry-run',
            '--output-format', 'json',
            '--set', $rectorRunValueObject->getSet(),
        ], $this->projectDir);
    }
}
