<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\Exception\ShouldNotHappenException;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

final class StepByStepComponent extends Component
{
    private const STEP_COUNT = 200;

    #[Url]
    public string $startingPhpVersion = '5.3';

    #[Url]
    public int $step = 0;

    public function render(): View
    {
        // to trigger javascript event in the component
        $this->dispatch(ComponentEvent::STEP_CHANGED);

        return view('livewire.step-by-step-component', [
            'rectorConfigContents' => $this->resolveRectorConfigContents($this->startingPhpVersion, $this->step),
            'stepCount' => self::STEP_COUNT,
        ]);
    }

    private function resolveRectorConfigContents(?string $startingPhpVersion, int $step): string
    {
        $baseFilePath = resource_path('step-by-step/base.php');
        $baseFileContents = FileSystem::read($baseFilePath);

        // @tpd enable since step X, where X > ~ 30, od not start with PHP first :)

        if ((float) $startingPhpVersion < 8.0) {
            $phpMethodContents = $this->resolvePhpMethod($step);
        } else {
            $namedArg = $this->resolvePhpNamedArg($step);
            $phpMethodContents = 'withPhpSets(' . $namedArg . ')';
        }

        $baseFileContents = Strings::replace($baseFileContents, '#__WITH_PHP_SETS#', '->' . $phpMethodContents);

        $baseFileContents = $this->decorateLevelMethod(
            $step,
            5,
            $baseFileContents,
            '__WITH_TYPE_DECLARATION_LEVEL',
            'withTypeCoverageLevel'
        );

        $baseFileContents = $this->decorateLevelMethod(
            $step,
            55,
            $baseFileContents,
            '__WITH_DEAD_CODE_LEVEL',
            'withDeadCodeLevel'
        );

        $baseFileContents = $this->decorateLevelMethod(
            $step,
            105,
            $baseFileContents,
            '__WITH_CODE_QUALITY_LEVEL',
            'withCodeQualityLevel'
        );

        return rtrim($baseFileContents) . ';' . PHP_EOL;
    }

    private function resolvePhpMethod(int $step): string
    {
        if ($step >= 5 && $step < 51) {
            return 'withPhp70Sets()';
        }

        if ($step >= 54) {
            return 'withPhp74Sets()';
        }

        return match ($step) {
            0 => 'withPhp53Sets()',
            1 => 'withPhp54Sets()',
            2 => 'withPhp55Sets()',
            3 => 'withPhp56Sets()',
            4 => 'withPhp70Sets()',
            // higher jump, let's wait a few steps
            51 => 'withPhp71Sets()',
            52 => 'withPhp72Sets()',
            53 => 'withPhp73Sets()',
            default => throw new ShouldNotHappenException()
        };
    }

    private function resolvePhpNamedArg(int $step): string
    {
        if ($step > 5 && $step < 51) {
            return 'php70: true';
        }

        if ($step >= 54) {
            return 'php74: true';
        }

        return match ($step) {
            0 => 'php53: true',
            1 => 'php54: true',
            2 => 'php55: true',
            3 => 'php56: true',
            4 => 'php70: true',
            // higher jump, let's wait a few steps
            51 => 'php71: true',
            52 => 'php72: true',
            53 => 'php73: true',
            default => throw new ShouldNotHappenException()
        };
    }

    private function decorateLevelMethod(
        int $step,
        int $startingStep,
        string $baseFileContents,
        string $placeholder,
        string $methodName
    ): string {
        if ($step <= $startingStep) {
            return Strings::replace($baseFileContents, '#' . $placeholder . '#', '');
        }

        return Strings::replace(
            $baseFileContents,
            '#' . $placeholder . '#',
            sprintf('->' . $methodName . '(%d)', (min($startingStep + 50, $step) - $startingStep))
        );
    }
}
