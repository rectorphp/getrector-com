<?php

declare(strict_types=1);

namespace Rector\Website\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

final class RectorFilterComponent extends Component
{
    #[Url]
    public ?string $query = '';

    public function render(): View
    {
        return view('livewire.rector-filter-component');
    }
}
