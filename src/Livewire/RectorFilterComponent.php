<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Component;

final class RectorFilterComponent extends Component
{
    public function render(): View
    {
        return view('livewire.rector-filter-component');
    }
}
