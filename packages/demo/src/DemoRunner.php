<?php

declare(strict_types=1);

namespace Rector\Website\Demo;

use Nette\Utils\Json;
use Nette\Utils\Random;
use Rector\ChangesReporting\Output\JsonOutputFormatter;
use Rector\Core\Application\FileProcessor;
use Rector\Core\Bootstrap\RectorConfigsResolver;
use Rector\Core\Configuration\Configuration;
use Rector\Core\DependencyInjection\RectorContainerFactory;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Core\ValueObjectFactory\ProcessResultFactory;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\Demo\Error\ErrorMessageNormalizer;
use Rector\Website\Demo\ValueObject\Option;
use function Sentry\captureException;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;
use Throwable;

/**
 * @see \Rector\Website\Demo\Tests\DemoRunnerTest
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

        $this->createTempFile($analyzedFilePath, $fileContent);
        $this->createTempFile($configPath, $configContent);

        $output = $this->processFile($analyzedFilePath, $configPath);

        return Json::decode($output, Json::FORCE_ARRAY);
    }

    private function createTempFile(string $filePath, string $fileContent): void
    {
        $this->smartFileSystem->dumpFile($filePath, $fileContent);

        register_shutdown_function(function () use ($filePath): void {
            $this->smartFileSystem->remove($filePath);
        });
    }

    private function processFile(string $fileToAnalyzePath, string $configPath): string
    {
        $rectorConfigsResolver = new RectorConfigsResolver();
        $configFileInfos = $rectorConfigsResolver->resolveFromConfigFileInfo(new SmartFileInfo($configPath));

        // TODO: ask Tomas if there is other way than creating container for every run - its the most time consuming operation in the run
        // Build DI container
        $rectorContainerFactory = new RectorContainerFactory();
        $container = $rectorContainerFactory->createFromConfigs($configFileInfos);

        /** @var Configuration $configuration */
        $configuration = $container->get(Configuration::class);

        // TODO: find better way how to disable progress bar
        $this->disableProgressBar($configuration);

        /** @var FileProcessor $fileProcessor */
        $fileProcessor = $container->get(FileProcessor::class);

        /** @var FileFactory $fileFactory */
        $fileFactory = $container->get(FileFactory::class);
        $files = $fileFactory->createFromPaths([$fileToAnalyzePath]);

        /** @var ProcessResultFactory $fileFactory */
        $processResultFactory = $container->get(ProcessResultFactory::class);

        // Goal is to process string
        foreach ($files as $file) {
            $fileProcessor->refactor($file);
        }

        $processResult = $processResultFactory->create($files);

        /** @var JsonOutputFormatter $outputFormatter */
        $outputFormatter = $container->get(JsonOutputFormatter::class);

        // TODO: report() should return output instead of echo it
        // Because report ECHO it, we need to capture
        ob_start();
        $outputFormatter->report($processResult);
        return (string) ob_get_clean();
    }

    private function disableProgressBar(Configuration $configuration): void
    {
        $privatesAccessor = new PrivatesAccessor();
        $privatesAccessor->setPrivateProperty($configuration, 'showProgressBar', false);
    }
}
