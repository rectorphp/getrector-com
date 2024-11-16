<?php

declare(strict_types=1);

use App\FileSystem\RectorFinder;

require __DIR__ . '/../vendor/autoload.php';

//$container = new \Illuminate\Container\Container();

$container = \App\DependencyInjection\DependencyInjectionContainerFactory::create();

// simple script to validate documented rule definitions

/** @var RectorFinder $rectorFinder */
$rectorFinder = $container->make(RectorFinder::class);
$ruleMetadatas = array_merge($rectorFinder->findCore(), $rectorFinder->findCommunity());

foreach ($ruleMetadatas as $ruleMetadata) {
    dump($ruleMetadata->getDescription());
    dump($ruleMetadata->getCodeSamples());
}

