<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use App\Enum\StepBreakpoint;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

final class StepByStepComponent extends Component
{
    /**
     * @var int
     */
    private const STEP_COUNT = 180;

    /**
     * @var string[]
     */
    private const PHP_VERSION_OPTIONS = [
        '5.3',
        '5.4',
        '5.5',
        '5.6',
        '7.0',
        '7.1',
        '7.2',
        '7.3',
        '7.4',
        '8.0',
        '8.1',
        '8.2',
        '8.3',
    ];

    #[Url]
    public string $startingPhpVersion = '5.3';

    #[Url]
    public int $step = 0;

    public ?Carbon $lastStepDateTime = null;

    public function render(): View
    {
        // to trigger javascript event in the component
        $this->dispatch(ComponentEvent::STEP_CHANGED);

        return view('livewire.step-by-step-component', [
            'rectorConfigContents' => $this->renderRectorConfigContents($this->startingPhpVersion, $this->step),
            'stepCount' => self::STEP_COUNT,
            'phpVersionOptions' => self::PHP_VERSION_OPTIONS,
            'progress' => round(($this->step / self::STEP_COUNT) * 100, 1),
        ]);
    }

    #[On(ComponentEvent::NEXT_STEP)]
    public function nextStep(): void
    {
        $this->step += $this->resolveStepSize();

        $this->step = min($this->step, self::STEP_COUNT);

        $this->lastStepDateTime = Carbon::now();
    }

    #[On(ComponentEvent::PREVIOUS_STEP)]
    public function previousStep(): void
    {
        $this->step -= $this->resolveStepSize();
        $this->step = max($this->step, 0);

        $this->lastStepDateTime = Carbon::now();
    }

    private function renderRectorConfigContents(string $startingPhpVersion, int $step): string
    {
        $view = view('step_by_step/_standalone/rector-config', [
            'step' => $step,
            'startingPhpVersion' => $startingPhpVersion,
            'hasNamedArgs' => (float) $startingPhpVersion >= 8.0 || $step > StepBreakpoint::PHP_80,
        ]);

        $rectorConfigContents = $view->render();
        return $this->trimExtraSpaces($rectorConfigContents);
    }

    private function trimExtraSpaces(string $contents): string
    {
        $contentLines = explode(PHP_EOL, $contents);

        $hasConfigStarted = false;

        $trimmedContentLines = [];
        foreach ($contentLines as $contentLine) {
            if ($contentLine === 'return RectorConfig::configure()') {
                $hasConfigStarted = true;
                $trimmedContentLines[] = $contentLine;
                continue;
            }

            if ($hasConfigStarted) {
                // skip empty newlines
                if (trim($contentLine) === '') {
                    continue;
                }

                $contentLine = '    ' . ltrim($contentLine);
            }

            $trimmedContentLines[] = $contentLine;
        }

        $trimmedContents = implode(PHP_EOL, $trimmedContentLines);
        return rtrim($trimmedContents, "\n ;") . ';';
    }

    private function resolveStepSize(): int
    {
        $stepSize = 1;

        if ($this->lastStepDateTime instanceof Carbon) {
            $diff = Carbon::now()->diffInSeconds($this->lastStepDateTime);
            // double click moves faster
            if (abs($diff) < 0.5) {
                $stepSize = 10;
            }
        }

        return $stepSize;
    }
}
