<?php

declare(strict_types=1);

namespace Rector\Website\Utils\Laravelize\Command;

use Illuminate\Console\Command;
use Rector\Website\Utils\Laravelize\TwigToBladeConverter;

final class TwigToBladeCommand extends Command
{
    /**
     * @api used by parent command
     *
     * @see https://laravel.com/docs/10.x/artisan#defining-input-expectations
     * @var string
     */
    protected $signature = 'app:twig-to-blade {paths}';

    public function __construct(
        private readonly TwigToBladeConverter $twigToBladeConverter
    ) {
        parent::__construct();
    }

    /**
     * @api used by parent command, maybe hanlde in the phpstan rule itself
     */
    public function handle(): int
    {
        /** @var string $paths */
        $paths = $this->argument('paths');

        $this->twigToBladeConverter->run($paths, $this->getOutput());

        $this->info('Templates are now converted to Blade!');

        return self::SUCCESS;
    }
}
