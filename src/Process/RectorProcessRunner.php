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
     * Do not change without changing run-demo.sh
     * @var string
     */
    private const ANALYZED_FILE_NAME = 'rector_analyzed_file.php';

    /**
     * Do not change without changing run-demo.sh
     * @var string
     */
    private const CONFIG_NAME = 'rector.yaml';

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

    /**
     * @var string
     */
    private $demoExecutablePath;

    public function __construct(
        string $hostDemoDir,
        string $localDemoDir,
        string $rectorDemoDockerImage,
        string $demoExecutablePath
    ) {
        $this->hostDemoDir = $hostDemoDir;
        $this->localDemoDir = $localDemoDir;
        $this->rectorDemoDockerImage = $rectorDemoDockerImage;
        $this->demoExecutablePath = $demoExecutablePath;
    }

    /**
     * @return mixed[]
     */
    public function run(RectorRun $rectorRun): array
    {
        $runId = $rectorRun->getId()->toString();
        $process = $this->createProcess($rectorRun);

        $this->createRunFile($runId, self::ANALYZED_FILE_NAME, $rectorRun->getContent());
        $this->createRunFile($runId, self::CONFIG_NAME, $rectorRun->getConfig());
        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getErrorOutput() ?: $process->getOutput();

        if ($process->isSuccessful()) {
            // If it was successful it will output valid json with result
            return Json::decode($output, Json::FORCE_ARRAY);
        }

        throw new RectorRunFailedException($output);
    }

    private function createProcess(RectorRun $rectorRun): Process
    {
        $runId = $rectorRun->getId()->toString();
        $volumeSourcePath = $this->hostDemoDir . '/' . $runId;

        return new Process([
            // Because user www-data runs docker owned by root, we need to use sudo
            'sudo', $this->demoExecutablePath,
            '-r', $runId,
            '-v', $volumeSourcePath,
            '-i', $this->rectorDemoDockerImage,
            '-d', $this->localDemoDir . '/' . $runId,
        ]);
    }

    private function createRunFile(string $runId, string $fileName, string $fileContent): void
    {
        $absolutePath = sprintf('%s/%s/%s', $this->localDemoDir, $runId, $fileName);

        FileSystem::write($absolutePath, $fileContent);
    }
}
