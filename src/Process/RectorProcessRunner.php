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
    private const ANALYZED_FILE_NAME = 'rector_analyzed_file.php';

    /**
     * @var string
     */
    private const RECTOR_RESULT_FILE_NAME = 'result.json';

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
        $process = $this->createProcess($rectorRun);
        $contentHash = $rectorRun->getContentHash();

        $this->registerCleanupOnShutdown($contentHash);

        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        if ($process->isSuccessful()) {
            return $this->getRectorResult($contentHash);
        }

        throw new RectorRunFailedException($this->getProcessOutput($contentHash));
    }

    private function createProcess(RectorRun $rectorRun): Process
    {
        $contentHash = $rectorRun->getContentHash();
        $volumeSourcePath = $this->hostDemoDir . '/' . $contentHash;

        $this->createTempPhpFile($contentHash, $rectorRun->getContent());

        return new Process([
            'docker', 'run',
            '--name', $contentHash,
            '--volume', $volumeSourcePath . ':/project',
            $this->rectorDemoDockerImage,
            'process', '/project/' . self::ANALYZED_FILE_NAME,
            '--output-format', 'json',
            '--output-file', '/project/' . self::RECTOR_RESULT_FILE_NAME,
            '--set', $rectorRun->getSetName(),
        ]);
    }

    private function registerCleanupOnShutdown(string $contentHash): void
    {
        register_shutdown_function(function () use ($contentHash): void {
            $this->removeContainer($contentHash);

            FileSystem::delete($this->localDemoDir . '/' . $contentHash);
        });
    }

    /**
     * @return mixed[]
     */
    private function getRectorResult(string $contentHash): array
    {
        $outputPath = sprintf('%s/%s/%s', $this->localDemoDir, $contentHash, self::RECTOR_RESULT_FILE_NAME);
        $result = FileSystem::read($outputPath);

        return Json::decode($result, Json::FORCE_ARRAY);
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

    private function createTempPhpFile(string $contentHash, string $fileContent): string
    {
        $tempFile = $contentHash . '/' . self::ANALYZED_FILE_NAME;

        FileSystem::write($this->localDemoDir . '/' . $tempFile, $fileContent);

        return $tempFile;
    }

    private function removeContainer(string $containerName): void
    {
        $process = new Process(['docker', 'rm', $containerName]);

        $process->run();
    }
}
