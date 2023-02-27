<?php

use Nette\Utils\FileSystem;

require __DIR__ . '/../vendor/autoload.php';

$cssFilePaths = [
    __DIR__ . '/../resources/css/app.scss',
    __DIR__ . '/../resources/css/menu.scss',
];

$exitCode = \Symfony\Component\Console\Command\Command::SUCCESS;

foreach ($cssFilePaths as $cssFilePath) {
    $cssFileContents = FileSystem::read($cssFilePath);

    $emMatches = \Nette\Utils\Strings::matchAll($cssFileContents, '#\dem\b#');

    if ($emMatches !== []) {
        echo sprintf('Found %d "em" uses in "%s" file', count($emMatches), realpath($cssFilePath)) . PHP_EOL;

        // lower only in case of good mood :)
        if (count($emMatches) > 85) {
            $exitCode = \Symfony\Component\Console\Command\Command::FAILURE;
        }
    }
}

exit($exitCode);
