<?php

// inspired at https://github.com/phpstan/phpstan/blob/master/playground-runner/bref.php

declare(strict_types=1);

use Jean85\PrettyVersions;
use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObjectFactory\Application\FileFactory;

require __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$rectorVersion = PrettyVersions::getVersion('rector/rector')->getPrettyVersion();

function clearTemp(): void
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator('/tmp/rector-bref', RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
}

// this closure is run with Bref, must be exact format
return function ($event) use ($rectorVersion) {
    clearTemp();

    // the event uses items from form
    $codeContent = $event['content'];
    $codePath = '/tmp/rector-bref/tmp.php';
    file_put_contents($codePath, $codeContent);

    $rootDir = getenv('LAMBDA_TASK_ROOT');

    $rectorConfigFileContent = $event['config'];
    $rectorConfigFilePath = '/tmp/rector-bref/rector-config-tmp.php';

    file_put_contents($rectorConfigFilePath, $rectorConfigFileContent);

    // @todo autoload files?
    require_once 'phar://' . $rootDir . '/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionUnionType.php';

    $rectorKernel = new RectorKernel();
    $container = $rectorKernel->createFromConfigs([$rectorConfigFilePath]);

    /** @var ApplicationFileProcessor $applicationFileProcessor */
    $applicationFileProcessor = $container->get(ApplicationFileProcessor::class);

    /** @var FileFactory $fileFactory */
    $fileFactory = $container->get(FileFactory::class);

    $configuration = new Configuration();
    $files = $fileFactory->createFromPaths([$rectorConfigFilePath], $configuration);

    $systemErrorsAndFileDiffs = $applicationFileProcessor->processFiles($files, $configuration);

    $fileDiffs = [];
    /** @var \Rector\Core\ValueObject\Reporting\FileDiff $fileDiff */
    foreach ($systemErrorsAndFileDiffs['file_diffs'] as $fileDiff) {
        $fileDiffs[] = $fileDiff->jsonSerialize();
    }

    $systemErrors = [];
    /** @var \Rector\Core\ValueObject\Error\SystemError $systemError */
    foreach ($systemErrorsAndFileDiffs['system_errors'] as $systemError) {
        $systemErrors[] = $systemError->jsonSerialize();
    }

    return [
        'file_diffs' => $fileDiffs,
        'system_errors' => $systemErrors,
        'version' => $rectorVersion,
    ];
};
