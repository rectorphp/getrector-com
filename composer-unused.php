<?php

// @see https://github.com/composer-unused/composer-unused

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;

return static function (Configuration $config): Configuration {
    // rector dependency for running "online demo"
    $config->addNamedFilter(\ComposerUnused\ComposerUnused\Configuration\NamedFilter::fromString('rector/rector'));

    return $config;
};
