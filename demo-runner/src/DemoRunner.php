<?php

declare(strict_types=1);

namespace Rector\WebsiteDemoRunner;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\WebsiteDemoRunner\Entity\RectorRun;
use Rector\WebsiteDemoRunner\Exception\DemoRunnerException;
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

    private readonly string $demoDir;

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
                $rectorRun->setFatalErrorMessages($jsonResult['fatal_errors'][0]);
            }

            $rectorRun->setJsonResult($jsonResult);
        } catch (\Throwable $throwable) {
            $normalizedMessage = $this->errorMessageNormalizer->normalize($throwable->getMessage());
            $rectorRun->setFatalErrorMessages($normalizedMessage);

            // @todo log sentry...?
        }
    }

    /**
     * @return mixed[]
     */
    private function processFilesContents(string $fileContent, string $configContent): array
    {
        $identifier = Random::generate(20);

        $analyzedFilePath = sys_get_temp_dir() . '/rector-demo/' . $identifier . '/' . self::ANALYZED_FILE_NAME;
        $configPath = sys_get_temp_dir() . '/rector-demo/' . $identifier . '/' . self::CONFIG_NAME;

        dump($analyzedFilePath);

        $this->smartFileSystem->dumpFile($analyzedFilePath, $fileContent);
        $this->smartFileSystem->dumpFile($configPath, $configContent);

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
        $this->smartFileSystem->remove([$analyzedFilePath, $configPath]);

        // error
        if ($process->getExitCode() !== self::EXIT_CODE_SUCCESS) {
            throw new DemoRunnerException($process->getErrorOutput() ?: $process->getOutput());
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
                throw new DemoRunnerException($errorMessage);
            }

            throw $jsonException;
        }
    }
}
