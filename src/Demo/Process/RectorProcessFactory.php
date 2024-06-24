<?php

declare(strict_types=1);

namespace App\Demo\Process;

use Symfony\Component\Process\Process;

final class RectorProcessFactory
{
    public function create(string $analyzedFilePath, string $configPath, ?string $extraFilePath): Process
    {
        $processOptions = [];

        // env() cannot be used in tests
        if (getenv('APP_ENV') !== 'prod') {
            $processOptions[] = PHP_BINARY;
        }

        $processOptions = array_merge($processOptions, [
            $this->resolveRectorBinPath(),
            'process',
            $analyzedFilePath,
            '--config',
            $configPath,
            '--output-format',
            'json',
        ]);

        // autoload custom Rector rule
        if ($extraFilePath) {
            $processOptions[] = '--autoload-file';
            $processOptions[] = $extraFilePath;
        }

        return new Process($processOptions);
    }

    private function resolveRectorBinPath(): string
    {
        // paths for phpunit differs based on test/demo, not sure why
        if (\defined('PHPUNIT_COMPOSER_INSTALL')) {
            return 'vendor/bin/rector';
        }

        return '../vendor/bin/rector';
    }
}
