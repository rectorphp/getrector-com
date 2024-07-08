<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Application;
use PHPStan\Reflection\ReflectionProvider;
use App\RuleFilter\ConfiguredDiffSamplesFactory;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\Sets\RectorSetsTreeProvider;
use Illuminate\Support\ServiceProvider;
use ReflectionProperty;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use PHPStan\DependencyInjection\ContainerFactory;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $phpstanContainerFactory = new ContainerFactory(getcwd());
        $phpstanContainer = $phpstanContainerFactory->create(
            __DIR__ . '/../storage/cache',
            [],
            []
        );

        $this->app->singleton(RectorSetsTreeProvider::class);
        $this->app->singleton(ReflectionProvider::class, function (Application $application) use ($phpstanContainer) : ReflectionProvider {
            return $phpstanContainer->getByType(ReflectionProvider::class);
        });

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
