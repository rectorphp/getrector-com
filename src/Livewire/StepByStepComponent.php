<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\ComponentEvent;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

final class StepByStepComponent extends Component
{
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
        ]);
    }

    private function resolveRectorConfigContents(?string $startingPhpVersion, ?int $step): string
    {
        $baseFilePath = resource_path('step-by-step/base.php');
        $baseFileContents = FileSystem::read($baseFilePath);

        // @tpd enable since step X, where X > ~ 30, od not start with PHP first :)

        if ((float) $startingPhpVersion < 8.0) {
            $phpMethodContents = match ($step) {
                0 => 'withPhp53Sets()',
                1 => 'withPhp54Sets()',
                2 => 'withPhp55Sets()',
                3 => 'withPhp56Sets()',
                4 => 'withPhp70Sets()',
                5 => 'withPhp71Sets()',
                6 => 'withPhp72Sets()',
                7 => 'withPhp73Sets()',
                default => 'withPhp74Sets()',
            };
        } else {
            $namedArg = match ($step) {
                0 => 'php53: true',
                1 => 'php54: true',
                2 => 'php55: true',
                3 => 'php56: true',
                4 => 'php70: true',
                5 => 'php71: true',
                6 => 'php72: true',
                7 => 'php73: true',
                default => 'php74: true',
            };

            $phpMethodContents = 'withPhpSets(' . $namedArg . ')';
        }

        $baseFileContents = Strings::replace($baseFileContents, '#__WITH_PHP_SETS#', $phpMethodContents);

        return $baseFileContents;
    }
}
