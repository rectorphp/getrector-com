<?php

declare(strict_types=1);

namespace Rector\Website\Process;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\Website\Error\ErrorMessageNormalizer;
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

    /**
     * @var ErrorMessageNormalizer
     */
    private $errorMessageNormalizer;

    public function __construct(
        ErrorMessageNormalizer $errorMessageNormalizer,
        string $hostDemoDir,
        string $localDemoDir,
        string $rectorDemoDockerImage,
        string $demoExecutablePath
    ) {
        $this->errorMessageNormalizer = $errorMessageNormalizer;
        $this->hostDemoDir = $hostDemoDir;
        $this->localDemoDir = $localDemoDir;
        $this->rectorDemoDockerImage = $rectorDemoDockerImage;
        $this->demoExecutablePath = $demoExecutablePath;
    }

    /**
     * @return mixed[]
     */
    public function run(string $fileContent, string $config): array
    {
        $identifier = Random::generate(20);

        $this->registerCleanupOnShutdown($identifier);

        $this->createTempFile($identifier . '/' . self::ANALYZED_FILE_NAME, $fileContent);
        $this->createTempFile($identifier . '/' . self::CONFIG_NAME, $config);

        $process = $this->createProcess($identifier);
        $process->run();

        if (! $process->isTerminated()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getErrorOutput() ?: $process->getOutput();

        if ($process->isSuccessful()) {
            try {
                // If it was successful it will output valid json with result
                return Json::decode($output, Json::FORCE_ARRAY);
            } catch (JsonException $jsonException) {
                // Do nothing, RectorRunFailedException will be thrown anyway
            }
        }

        $output = $this->errorMessageNormalizer->normalize($output);

        throw new RectorRunFailedException($output);
    }

    private function registerCleanupOnShutdown(string $directory): void
    {
        register_shutdown_function(function () use ($directory): void {
            FileSystem::delete($this->localDemoDir . '/' . $directory);
        });
    }

    private function createTempFile(string $filePath, string $fileContent): void
    {
        FileSystem::write($this->localDemoDir . '/' . $filePath, $fileContent);
    }

    private function createProcess(string $identifier): Process
    {
        $volumeSourcePath = $this->hostDemoDir . '/' . $identifier;

        return new Process([
            // Because user www-data runs docker owned by root, we need to use sudo
            'sudo', $this->demoExecutablePath,
            '-n', $identifier,
            '-v', $volumeSourcePath,
            '-i', $this->rectorDemoDockerImage,
        ]);
    }
}
