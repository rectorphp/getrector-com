<?php

// @see https://github.com/composer-unused/composer-unused

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

return static function (Configuration $config): Configuration {
    // rector dependency for running "online demo"
    $config->addNamedFilter(NamedFilter::fromString('rector/rector'));

    // used for jajodb patching
    $config->addNamedFilter(NamedFilter::fromString('symplify/vendor-patches'));

    // false positive
    $config->addNamedFilter(NamedFilter::fromString('symfony/asset'));

    // used in bootstrap.php to load .env file and set basic symfony env + debug state
    $config->addNamedFilter(NamedFilter::fromString('symfony/dotenv'));

    // used in bundles.php to twig-related configuration for Symfony
    $config->addNamedFilter(NamedFilter::fromString('symfony/twig-bundle'));
    $config->addNamedFilter(NamedFilter::fromString('symfony/webpack-encore-bundle'));

    return $config;
};
