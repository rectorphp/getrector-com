<?php

declare(strict_types=1);

namespace Rector\Website;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\Website\Entity\RectorRun;
use Rector\Website\Exception\RectorRunFailedException;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Utils\ErrorMessageNormalizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * @see \Rector\Website\Tests\DemoRunnerTest
 */
final class DemoRunner
{
    /**
     * @var string
     */
    private const ANALYZED_FILE_NAME = 'rector_analyzed_file.php';

    /**
     * @var string
     */
    private const CONFIG_NAME = 'rector.php';

    /**
     * @var int
     */
    private const EXIT_CODE_SUCCESS = 0;

    private readonly string $demoDir;

    public function __construct(
        private readonly ErrorMessageNormalizer $errorMessageNormalizer,
        private readonly Filesystem $filesystem,
    ) {
        $this->demoDir = __DIR__ . '/../storage/demo';
    }

    public function processRectorRun(RectorRun $rectorRun): void
    {
        try {
            $jsonResult = $this->processFilesContents($rectorRun->getContent(), $rectorRun->getConfig());
            if (isset($jsonResult['fatal_errors'])) {
                $rectorRun->setFatalErrorMessage($jsonResult['fatal_errors'][0]);
            }

            $rectorRun->setJsonResult($jsonResult);
        } catch (Throwable $throwable) {
            $normalizedMessage = $this->errorMessageNormalizer->normalize($throwable->getMessage());
            $rectorRun->setFatalErrorMessage($normalizedMessage);

            // @TODO log to monolog
        }
    }

    private function processRun(string $analyzedFilePath, string $configPath): Process
    {
        if (getenv('APP_ENV') !== 'prod') {
            $process = new Process([
                PHP_BINARY,
                // paths for phpunit differs based on test/demo, not sure why
                \defined('PHPUNIT_COMPOSER_INSTALL') ? 'vendor/bin/rector' : '../vendor/bin/rector',
                'process',
                $analyzedFilePath,
                '--config',
                $configPath,
                '--output-format',
                'json',
            ]);
        } else {
            $process = new Process([
                // paths for phpunit differs based on test/demo, not sure why
                \defined('PHPUNIT_COMPOSER_INSTALL') ? 'vendor/bin/rector' : '../vendor/bin/rector',
                'process',
                $analyzedFilePath,
                '--config',
                $configPath,
                '--output-format',
                'json',
            ]);
        }

        $process->run();
        return $process;
    }

    /**
     * @return mixed[]
     */
    private function processFilesContents(string $fileContent, string $configContent): array
    {
        $identifier = Random::generate(20);

        $analyzedFilePath = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR . self::ANALYZED_FILE_NAME;
        $configPath = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR . self::CONFIG_NAME;

        $this->filesystem->dumpFile($analyzedFilePath, $fileContent);
        $this->filesystem->dumpFile($configPath, $configContent);

        $temporaryFilePaths = [$analyzedFilePath, $configPath];

        $process = $this->processRun($analyzedFilePath, $configPath);

        // remove temporary files
        $this->filesystem->remove($temporaryFilePaths);

        // error
        if ($process->getExitCode() !== self::EXIT_CODE_SUCCESS) {
            throw new RectorRunFailedException($process->getErrorOutput() ?: $process->getOutput());
        }

        $output = $process->getOutput();
        if ($output === '') {
            throw new RectorRunFailedException('Empty result returned');
        }

        // is valid json?
        try {
            return Json::decode($output, Json::FORCE_ARRAY);
        } catch (JsonException $jsonException) {
            if ($jsonException->getMessage() === 'Syntax error') {
                $errorMessage = 'Invalid json syntax in "vendor/bin/rector" process output: ' . PHP_EOL . PHP_EOL . $output;
                throw new ShouldNotHappenException($errorMessage);
            }

            throw $jsonException;
        }
    }
}
