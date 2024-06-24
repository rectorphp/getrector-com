<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Process;

use Symfony\Component\Process\Process;

final class RectorProcessFactory
{
    public function create(string $analyzedFilePath, string $configPath, ?string $extraFilePath): Process
    {
        // dev and test
        if (app('env') !== 'prod') {
            $processOptions = [
                PHP_BINARY,
                $this->resolveRectorBinPath(),
                'process',
                $analyzedFilePath,
                '--config',
                $configPath,
                '--output-format',
                'json',
                '--debug',
            ];
        } else {
            $processOptions = [
                // paths for phpunit differs based on test/demo, not sure why
                $this->resolveRectorBinPath(),
                'process',
                $analyzedFilePath,
                '--config',
                $configPath,
                '--output-format',
                'json',
            ];
        }

        // autoload custom Rector rule
        if ($extraFilePath) {
            $processOptions[] = '--autoload-file';
            $processOptions[] = $extraFilePath;
        }

        $process = new Process($processOptions);

        dump($process->getCommandLine());
        die;

        $process->run();

        return $process;
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
