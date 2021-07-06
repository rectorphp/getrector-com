<?php

declare(strict_types=1);

namespace Rector\Website\Demo;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Error\ErrorMessageNormalizer;
use Rector\Website\Demo\Exception\RunnerException;
use Rector\Website\Demo\ValueObject\Option;
use function Sentry\captureException;
use Symfony\Component\Process\Process;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileSystem;
use Throwable;

/**
 * @see \Rector\Website\Tests\Demo\DemoRunnerTest
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

    private string $demoDir;

    public function __construct(
        private ErrorMessageNormalizer $errorMessageNormalizer,
        private SmartFileSystem $smartFileSystem,
        ParameterProvider $parameterProvider
    ) {
        $this->demoDir = $parameterProvider->provideStringParameter(Option::DEMO_DIR);
    }

    public function processRectorRun(RectorRun $rectorRun): void
    {
        try {
            $jsonResult = $this->processFilesContents($rectorRun->getContent(), $rectorRun->getConfig());
            $rectorRun->setJsonResult($jsonResult);
        } catch (Throwable $throwable) {
            $normalizedMessage = $this->errorMessageNormalizer->normalize($throwable->getMessage());
            $rectorRun->setFatalErrorMessage($normalizedMessage);

            // @TODO change to monolog
            // Log to sentry
            captureException($throwable);
        }
    }

    /**
     * @return mixed[]
     */
    private function processFilesContents(string $fileContent, string $configContent): array
    {
        $identifier = Random::generate(20);

        $analyzedFilePath = $this->demoDir . '/' . $identifier . '/' . self::ANALYZED_FILE_NAME;
        $configPath = $this->demoDir . '/' . $identifier . '/' . self::CONFIG_NAME;

        $this->smartFileSystem->dumpFile($analyzedFilePath, $fileContent);
        $this->smartFileSystem->dumpFile($configPath, $configContent);

        $temporaryFilePaths = [$analyzedFilePath, $configPath];

        $process = new Process([
            '../vendor/bin/rector',
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

        $output = $process->getOutput();
        if ($output === '') {
            return [];
        }

        try {
            return Json::decode($output, Json::FORCE_ARRAY);
        } catch (JsonException $jsonException) {
            if (str_contains($output, '[ERROR]')) {
                throw new RunnerException($output);
            }
        }
    }
}
