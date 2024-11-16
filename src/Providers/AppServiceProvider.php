<?php

declare(strict_types=1);

namespace App\Providers;

use App\RuleFilter\ConfiguredDiffSamplesFactory;
use App\RuleFilter\Markdown\MarkdownDiffer;
use App\Sets\RectorSetsTreeProvider;
use Illuminate\Support\ServiceProvider;
use ReflectionProperty;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RectorSetsTreeProvider::class);

        $this->app->singleton(SymfonyStyle::class, function (): SymfonyStyle {
            return new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());
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
