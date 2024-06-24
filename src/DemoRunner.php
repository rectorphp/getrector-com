<?php

declare(strict_types=1);

namespace Rector\Website;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\Website\Demo\Process\RectorProcessFactory;
use Rector\Website\Entity\AbstractRectorRun;
use Rector\Website\Exception\RectorRunFailedException;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Utils\ClassNameResolver;
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
        private readonly RectorProcessFactory $rectorProcessFactory,
    ) {
        $this->demoDir = __DIR__ . '/../storage/demo';
    }

    public function processRectorRun(AbstractRectorRun $rectorRun): void
    {
        try {
            $jsonResult = $this->processFilesContents($rectorRun->getContent(), $rectorRun->getRunnablePhp());
            if (isset($jsonResult['fatal_errors'])) {
                $rectorRun->setFatalErrorMessage($jsonResult['fatal_errors'][0]);
            }

            $rectorRun->setJsonResult($jsonResult);
        } catch (Throwable $throwable) {
            $normalizedMessage = $this->errorMessageNormalizer->normalize($throwable->getMessage());
            $rectorRun->setFatalErrorMessage($normalizedMessage);
        }
    }

    /**
     * @return mixed[]
     */
    private function processFilesContents(string $fileContent, string $rectorConfig): array
    {
        $identifier = Random::generate(20);

        $analyzedFilePath = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR . self::ANALYZED_FILE_NAME;
        $configPath = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR . self::CONFIG_NAME;

        // this can be both rector config or rector rule
        // for the latter, append simple config to be part of the file
        $extraFileContents = null;
        $extraFilePath = null;
        if (str_contains($rectorConfig, 'extends') && str_contains($rectorConfig, 'Rector\Rector\AbstractRector')) {
            // is Rector rule
            $extraFileContents = $rectorConfig;
            $analyzedFilePath = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR . 'CustomRuleRector.php';

            $rectorClassName = ClassNameResolver::resolveFromFileContents($rectorConfig, $analyzedFilePath);
            $rectorConfig = sprintf(
                'return \Rector\Config\RectorConfig::configure()->withRules([%s::class]);' . PHP_EOL,
                $rectorClassName
            );
        }

        // prepare files
        $this->filesystem->dumpFile($analyzedFilePath, $fileContent);
        $this->filesystem->dumpFile($configPath, $rectorConfig);
        if ($extraFileContents !== null) {
            $this->filesystem->dumpFile($analyzedFilePath, $extraFileContents);
        }

        $temporaryFilePaths = [$analyzedFilePath, $configPath];
        if ($analyzedFilePath) {
            $temporaryFilePaths[] = $analyzedFilePath;
        }

        $rectorProcess = $this->rectorProcessFactory->create($analyzedFilePath, $configPath, $extraFilePath);

        // remove temporary files
        $this->filesystem->remove($temporaryFilePaths);

        // error
        if ($rectorProcess->getExitCode() !== self::EXIT_CODE_SUCCESS) {
            throw new RectorRunFailedException($rectorProcess->getErrorOutput() ?: $rectorProcess->getOutput());
        }

        $output = $rectorProcess->getOutput();
        if ($output === '') {
            throw new RectorRunFailedException('Empty result returned');
        }

        // is valid json?
        try {
            return Json::decode($output, true);
        } catch (JsonException $jsonException) {
            if ($jsonException->getMessage() === 'Syntax error') {
                $errorMessage = 'Invalid json syntax in "vendor/bin/rector" process output: ' . PHP_EOL . PHP_EOL . $output;
                throw new ShouldNotHappenException($errorMessage);
            }

            throw $jsonException;
        }
    }
}
