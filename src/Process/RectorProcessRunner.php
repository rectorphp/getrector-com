<?php

declare(strict_types=1);

namespace Rector\Website\Process;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Rector\Website\Entity\RectorRun;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class RectorProcessRunner
{
    /**
     * @var string
     */
    private const ANALYZED_FILE_NAME = 'rector_analyzed_file.php';

    /**
     * @var string
     */
    private $hostDemoDir;

    /**
     * @var string
     */
    private $localDemoDir;

    /**
     * @var string
     */
    private $rectorDemoDockerImage;


    public function __construct(string $hostDemoDir, string $localDemoDir, string $rectorDemoDockerImage)
    {
        $this->hostDemoDir = $hostDemoDir;
        $this->localDemoDir = $localDemoDir;
        $this->rectorDemoDockerImage = $rectorDemoDockerImage;
    }

    /**
     * @return mixed[]
     */
    public function run(RectorRun $rectorRun): array
    {
        $contentHash = $rectorRun->getContentHash();

        $this->createTempPhpFile($contentHash, $rectorRun->getContent());
        $process = $this->createProcess($contentHash, $rectorRun->getSetName());

        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        $output = $this->getProcessOutput($contentHash);

        $this->cleanup($contentHash);

        try {
            return Json::decode($output, Json::FORCE_ARRAY);
        } catch (JsonException $jsonException) {
            throw new RectorRunFailedException($output);
        }
    }

    private function createTempPhpFile(string $contentHash, string $fileContent): string
    {
        $tempFile = $contentHash . '/' . self::ANALYZED_FILE_NAME;

        FileSystem::write($this->localDemoDir . '/' . $tempFile, $fileContent);

        return $tempFile;
    }

    private function createProcess(string $contentHash, string $setName): Process
    {
        $volumeSourcePath = $this->hostDemoDir . '/' . $contentHash;

        return new Process([
            'docker', 'run',
            '--name', $contentHash,
            '-v', $volumeSourcePath . ':/project:ro',
            $this->rectorDemoDockerImage,
            'process', '/project/' . self::ANALYZED_FILE_NAME,
            '--dry-run',
            '--output-format', 'json',
            '--set', $setName,
        ]);
    }

    private function getProcessOutput(string $containerName): string
    {
        $process = new Process(['docker', 'logs', $containerName]);

        $process->run();

        $errorOutput = $process->getErrorOutput();

        if ($errorOutput) {
            return $errorOutput;
        }

        return $process->getOutput();
    }

    private function cleanup(string $contentHash): void
    {
        $this->removeContainer($contentHash);

        FileSystem::delete($this->localDemoDir . '/' . $contentHash);
    }

    private function removeContainer(string $containerName): void
    {
        $process = new Process(['docker', 'rm', $containerName]);

        $process->run();
    }
}
