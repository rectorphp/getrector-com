<?php

declare(strict_types=1);

use App\DependencyInjection\DependencyInjectionContainerFactory;
use App\FileSystem\RectorFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/../vendor/autoload.php';

// simple script to validate documented rule definitions
$container = DependencyInjectionContainerFactory::create();

/** @var RectorFinder $rectorFinder */
$rectorFinder = $container->make(RectorFinder::class);
$ruleMetadatas = array_merge($rectorFinder->findCore(), $rectorFinder->findCommunity());

$symfonyStyle = $container->make(SymfonyStyle::class);
$symfonyStyle->title(sprintf('Found %d rule definitions', count($ruleMetadatas)));

$areRulesValid = true;

foreach ($ruleMetadatas as $ruleMetadata) {
    $isValid = true;
    if ($ruleMetadata->getDescription() === '') {
        $isValid = false;
        $symfonyStyle->error(sprintf('Rule "%s" is missing description. Fill it first to enable rule search', $ruleMetadata->getRuleShortClass()));
    }

    if ($ruleMetadata->getCodeSamples() === []) {
        $symfonyStyle->error(sprintf('Rule "%s" is missing code samples. Fill it first to enable rule search', $ruleMetadata->getRuleShortClass()));
        $isValid = false;
    }

    if ($isValid === false) {
        $areRulesValid = false;
        continue;
    }

    $symfonyStyle->writeln(sprintf('* Rule "%s" is valid', $ruleMetadata->getRuleShortClass()));
}


if ($areRulesValid) {
    $symfonyStyle->success(sprintf('All %d rules are valid', count($ruleMetadatas)));
    exit(Command::SUCCESS);
}

exit(Command::FAILURE);
