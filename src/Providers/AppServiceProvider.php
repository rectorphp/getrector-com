<?php

declare(strict_types=1);

namespace App\Providers;

use App\RuleFilter\ConfiguredDiffSamplesFactory;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\Sets\RectorSetsTreeProvider;
use Illuminate\Support\ServiceProvider;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use ReflectionProperty;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $lazyContainerFactory = new LazyContainerFactory();
        $rectorConfig = $lazyContainerFactory->create();

        $this->app->singleton(RectorConfig::class, fn (): RectorConfig => $rectorConfig);

        $this->app->singleton(RectorSetsTreeProvider::class);

        $this->app->singleton(MarkdownDiffer::class, function (): MarkdownDiffer {
            // this is required to show full diffs from start to end
            $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder('');
            $contextLinesReflectionProperty = new ReflectionProperty($unifiedDiffOutputBuilder, 'contextLines');
            $contextLinesReflectionProperty->setValue($unifiedDiffOutputBuilder, 10000);

            return new MarkdownDiffer(new Differ($unifiedDiffOutputBuilder));
        });

        $this->app->singleton(ConfiguredDiffSamplesFactory::class);
    }
}
