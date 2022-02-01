<?php

// inspired at https://github.com/phpstan/phpstan/blob/master/playground-runner/bref.php

declare(strict_types=1);

use Jean85\PrettyVersions;
use Rector\WebsiteDemoRunner\DemoRunner;
use Rector\WebsiteDemoRunner\Entity\RectorRun;
use Rector\WebsiteDemoRunner\ErrorMessageNormalizer;
use Symplify\SmartFileSystem\SmartFileSystem;

require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

function prepareTempDirectory(): void
{
    $tempDirectory = sys_get_temp_dir() . '/rector-bref';

    // create empty directory
    if (! file_exists($tempDirectory)) {
        \Nette\Utils\FileSystem::createDir($tempDirectory);
        return;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(sys_get_temp_dir() . '/rector-bref', RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
}

$rectorVersion = PrettyVersions::getVersion('rector/rector')->getPrettyVersion();

// this closure is run with Bref, must be exact format
return function ($event) use ($rectorVersion) {
    prepareTempDirectory();

    // the event uses items from form

    // @todo what for?
    $rootDir = getenv('LAMBDA_TASK_ROOT');

    // @todo temporarily files here - fix submit form, then should be removed after ?? ...
    $processedFileContent = $event['content'] ?? file_get_contents(__DIR__ . '/../data/demo/DemoFile.php');
    $rectorConfigFileContent = $event['config'] ?? file_get_contents(__DIR__ . '/../data/demo/demo-config.php');

    // @todo autoload files?
    // require_once 'phar://' . $rootDir . '/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionUnionType.php';

    $demoRunner = new DemoRunner(new ErrorMessageNormalizer(), new SmartFileSystem());
    $rectorRun = new RectorRun($rectorConfigFileContent, $processedFileContent);
    $demoRunner->processRectorRun($rectorRun);

    $jsonResult = $rectorRun->getJsonResult();

    return [
        'file_diffs' => $jsonResult['file_diffs'],
        'system_errors' => $rectorRun->getFatalErrorMessage(),
        'version' => $rectorVersion,
    ];
};
