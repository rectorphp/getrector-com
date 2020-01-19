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
    private const CONFIG_NAME = 'rector.yaml';

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
        $runId = $rectorRun->getId()->toString();

        $this->registerCleanupOnShutdown($runId);

        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        if ($process->isSuccessful()) {
            return $this->getRectorResult($runId);
        }

        throw new RectorRunFailedException($this->getProcessOutput($runId));
    }

    private function createProcess(RectorRun $rectorRun): Process
    {
        $runId = $rectorRun->getId()->toString();
        $volumeSourcePath = $this->hostDemoDir . '/' . $runId;

        $this->createTempRunFile($runId, self::ANALYZED_FILE_NAME, $rectorRun->getContent());
        $this->createTempRunFile($runId, self::CONFIG_NAME, $rectorRun->getConfig());

        return new Process([
            'docker', 'run',
            '--name', $runId,
            '--volume', $volumeSourcePath . ':/project',
            $this->rectorDemoDockerImage,
            'process', '/project/' . self::ANALYZED_FILE_NAME,
            '--output-format', 'json',
            '--output-file', '/project/' . self::RECTOR_RESULT_FILE_NAME,
            '--config', '/project/' . self::CONFIG_NAME,
        ]);
    }

    private function registerCleanupOnShutdown(string $runId): void
    {
        register_shutdown_function(function () use ($runId): void {
            $this->removeContainer($runId);

            FileSystem::delete($this->localDemoDir . '/' . $runId);
        });
    }

    /**
     * @return mixed[]
     */
    private function getRectorResult(string $runId): array
    {
        $outputPath = sprintf('%s/%s/%s', $this->localDemoDir, $runId, self::RECTOR_RESULT_FILE_NAME);
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

    private function createTempRunFile(string $runId, string $fileName, string $fileContent): void
    {
        $absolutePath = sprintf('%s/%s/%s', $this->localDemoDir, $runId, $fileName);

        FileSystem::write($absolutePath, $fileContent);
    }

    private function removeContainer(string $containerName): void
    {
        $process = new Process(['docker', 'rm', $containerName]);

        $process->run();
    }
}
