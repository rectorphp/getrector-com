<?php

declare(strict_types=1);

namespace App;

use App\Demo\Process\RectorProcessFactory;
use App\Entity\AbstractRectorRun;
use App\Exception\RectorRunFailedException;
use App\Exception\ShouldNotHappenException;
use App\Utils\ClassNameResolver;
use App\Utils\ErrorMessageNormalizer;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

/**
 * @see \App\Tests\DemoRunnerTest
 */
final readonly class DemoRunner
{
    private const string ANALYZED_FILE_NAME = 'rector_analyzed_file.php';

    private const string CONFIG_NAME = 'rector.php';

    private const int EXIT_CODE_SUCCESS = 0;

    private string $demoDir;

    public function __construct(
        private ErrorMessageNormalizer $errorMessageNormalizer,
        private Filesystem $filesystem,
        private RectorProcessFactory $rectorProcessFactory,
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

        $directoryConfig = $this->demoDir . DIRECTORY_SEPARATOR . $identifier . DIRECTORY_SEPARATOR;
        $analyzedFilePath = $directoryConfig . self::ANALYZED_FILE_NAME;
        $configPath = $directoryConfig . self::CONFIG_NAME;

        // this can be both rector config or rector rule
        // for the latter, append simple config to be part of the file
        $extraFileContents = null;
        $extraFilePath = null;
        if (str_contains($rectorConfig, 'extends') && str_contains($rectorConfig, AbstractRector::class)) {
            // is Rector rule
            $extraFileContents = $rectorConfig;
            $extraFilePath = $directoryConfig . 'CustomRuleRector.php';

            $rectorClassName = ClassNameResolver::resolveFromFileContents($rectorConfig, $analyzedFilePath);
            $rectorConfig = sprintf(
                '<?php%s return ' . RectorConfig::class . '::configure()->withRules([%s::class]);' . PHP_EOL,
                PHP_EOL . PHP_EOL,
                $rectorClassName
            );
        }

        // prepare files
        $this->filesystem->dumpFile($analyzedFilePath, $fileContent);
        $this->filesystem->dumpFile($configPath, $rectorConfig);
        if ($extraFileContents !== null) {
            $this->filesystem->dumpFile($extraFilePath, $extraFileContents);
        }

        $rectorProcess = $this->rectorProcessFactory->create($analyzedFilePath, $configPath, $extraFilePath);
        $rectorProcess->run();

        // remove temporary directory
        $this->filesystem->remove($directoryConfig);

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
