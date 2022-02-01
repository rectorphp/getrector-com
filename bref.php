<?php

// inspired at https://github.com/phpstan/phpstan/blob/master/playground-runner/bref.php

declare(strict_types=1);

use Jean85\PrettyVersions;

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

return function ($event) use ($rectorVersion) {
    clearTemp();

    // the event uses items from form
    $codeContent = $event['content'];
    $codePath = '/tmp/rector-bref/tmp.php';
    file_put_contents($codePath, $codeContent);

    $rootDir = getenv('LAMBDA_TASK_ROOT');

    $rectorConfigFileContent = $event['config'];
    $rectorConfigFile = '/tmp/rector-bref/rector-config-tmp.php';

    file_put_contents($rectorConfigFile, $rectorConfigFileContent);

    // @todo autoload files?
    require_once 'phar://' . $rootDir . '/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionUnionType.php';

//    $containerFactory = new \PHPStan\DependencyInjection\ContainerFactory('/tmp');
//    $container = $containerFactory->create('/tmp', [sprintf('%s/config.level%s.neon', $containerFactory->getConfigDirectory(), $level), $finalConfigFile], [$codePath]);

//    /** @var \PHPStan\Analyser\Analyser $analyser */
//    $analyser = $container->getByType(\PHPStan\Analyser\Analyser::class);
//    $results = $analyser->analyse([$codePath], null, null, false, [$codePath])->getErrors();

    error_clear_last();

    $errors = [];
    foreach ($results as $result) {
        if (is_string($result)) {
            $errors[] = [
                'message' => $result,
                'line' => 1,
            ];
            continue;
        }

        $errors[] = [
            'message' => $result->getMessage(),
            'line' => $result->getLine(),
        ];
    }

    return ['result' => $errors, 'version' => $rectorVersion];
};
