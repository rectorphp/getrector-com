<?php

namespace Rector\Website\Utils\Laravelize\Command;

use Illuminate\Console\Command;
use Rector\Website\Utils\Laravelize\TwigToBladeConverter;

final class TwigToBladeCommand extends Command
{
    /**
     * @see https://laravel.com/docs/10.x/artisan#defining-input-expectations
     * @var string
     */
    public $signature = 'app:twig-to-blade {paths}';

    public function __construct(
        private readonly TwigToBladeConverter $twigToBladeConverter
    ) {
        parent::__construct();
    }

    public function handle(TwigToBladeConverter $twigToBladeConverter): int
    {
        /** @var string $paths */
        $paths = $this->argument('paths');

        $this->twigToBladeConverter->run($paths, $this->getOutput());

        $this->info('Templates are no converted to Blade');

        return self::SUCCESS;
    }
}
