<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\Kernel;
use Rector\Website\Utils\Laravelize\Command\TwigToBladeCommand;

final class ConsoleKernel extends Kernel
{
    /**
     * @var array<class-string<Command>>
     */
    protected $commands = [TwigToBladeCommand::class];
}
