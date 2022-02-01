<?php

declare(strict_types=1);

namespace Rector\WebsiteDemoRunner;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
<<<<<<< HEAD:packages/Demo/DemoRunner.php
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Error\ErrorMessageNormalizer;
use Rector\Website\Demo\Exception\Process\RectorRunFailedException;
use Rector\Website\Demo\ValueObject\Option;
use Rector\Website\Exception\ShouldNotHappenException;
=======
use Rector\WebsiteDemoRunner\Entity\RectorRun;
use Rector\WebsiteDemoRunner\Exception\DemoRunnerException;
>>>>>>> separate demo-runner to own directory:demo-runner/src/DemoRunner.php
use Symfony\Component\Process\Process;
use Symplify\SmartFileSystem\SmartFileSystem;

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
<<<<<<< HEAD:packages/Demo/DemoRunner.php

    private readonly string $demoDir;
=======
>>>>>>> separate demo-runner to own directory:demo-runner/src/DemoRunner.php

    public function __construct(
        private readonly ErrorMessageNormalizer $errorMessageNormalizer,
        private readonly SmartFileSystem $smartFileSystem,
    ) {
    }

    public function processRectorRun(RectorRun $rectorRun): void
    {
        try {
            $jsonResult = $this->processFilesContents($rectorRun->getContent(), $rectorRun->getConfig());
            if (isset($jsonResult['fatal_errors'])) {
                $rectorRun->setFatalErrorMessage($jsonResult['fatal_errors'][0]);
            }

            $rectorRun->setJsonResult($jsonResult);
        } catch (\Throwable $throwable) {
            $normalizedMessage = $this->errorMessageNormalizer->normalize($throwable->getMessage());
            $rectorRun->setFatalErrorMessage($normalizedMessage);

            // @todo log sentry...?
        }
    }

    /**
     * @return mixed[]
     */
    private function processFilesContents(string $fileContent, string $configContent): array
    {
        $identifier = Random::generate(20);

        $analyzedFilePath = sys_get_temp_dir() . '/tmp/rector-demo/' . $identifier . '/' . self::ANALYZED_FILE_NAME;
        $configPath = sys_get_temp_dir() . '/tmp/rector-demo/' . $identifier . '/' . self::CONFIG_NAME;

        $this->smartFileSystem->dumpFile($analyzedFilePath, $fileContent);
        $this->smartFileSystem->dumpFile($configPath, $configContent);

        $temporaryFilePaths = [$analyzedFilePath, $configPath];

        $process = new Process([
            'vendor/bin/rector',
            'process',
            $analyzedFilePath,
            '--config',
            $configPath,
            '--output-format',
            'json',
        ]);

        $process->run();

        // remove temporary files
        $this->smartFileSystem->remove($temporaryFilePaths);

<<<<<<< HEAD:packages/Demo/DemoRunner.php
        // error
        if ($process->getExitCode() !== self::EXIT_CODE_SUCCESS) {
            throw new RectorRunFailedException($process->getErrorOutput() ?: $process->getOutput());
=======
        if ($process->getExitCode() !== self::EXIT_CODE_SUCCESS) {
            throw new DemoRunnerException($process->getErrorOutput() ?: $process->getOutput());
>>>>>>> separate demo-runner to own directory:demo-runner/src/DemoRunner.php
        }

        $output = $process->getOutput();
        if ($output === '') {
            return [];
        }

        // is valid json?
        try {
            return Json::decode($output, Json::FORCE_ARRAY);
        } catch (JsonException $jsonException) {
            if ($jsonException->getMessage() === 'Syntax error') {
                $errorMessage = 'Invalid json syntax in "vendor/bin/rector" process output: ' . PHP_EOL . PHP_EOL . $output;
<<<<<<< HEAD:packages/Demo/DemoRunner.php
                throw new ShouldNotHappenException($errorMessage);
=======
                throw new DemoRunnerException($errorMessage);
>>>>>>> separate demo-runner to own directory:demo-runner/src/DemoRunner.php
            }

            throw $jsonException;
        }
    }
}
