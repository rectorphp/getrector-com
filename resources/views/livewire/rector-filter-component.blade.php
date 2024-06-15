<in@php
    /** @var \Rector\Website\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
    /** @var \Rector\Website\RuleFilter\ValueObject\RectorSet[] $rectorSets */
@endphp

<div>
    <input
        placeholder="Type to start searching a rule"
        type="text"
        class="form-control d-inline"
        style="width: 15em"
        wire:model.live.debounce.300ms="query"
    >

    <label for="node_type" class="ms-4">Element to change:</label>

    <select class="form-select d-inline ms-3" name="node_type" style="width: 15em" wire:model.live="nodeType">
        <option value="">Any element</option>
        @foreach ($nodeTypeSelectOptions as $optionValue => $optionName)
            <option value="{{ $optionValue }}">{{ $optionName }}</option>
        @endforeach
    </select>

    <div class="clearfix mb-2"></div>

    <!-- @see https://livewire.laravel.com/docs/wire-model#customizing-the-debounce -->

    {{-- by hooked nodes, but in human words :) --}}

    <br>

    @foreach ($filteredRules as $filteredRule)
        <div class="mb-3 mt-2 card
                @if ($filteredRule->isConfigurable())
                    border-primary
                @endif
            " style="--bs-border-opacity: .5;"
        >
            <div class="card-body pt-2 pb-2 ps-4 pe-4">
                @if ($filteredRule->isConfigurable())
                    <div style="float: right" class="mt-3 me-2">
                        <span class="badge bg-primary">Configurable</span>
                    </div>
                @endif

                <h3 class="mb-4">{{ $filteredRule->getRuleShortClass() }}</h3>

                <p>{{ $filteredRule->getDescription() }}</p>

                <div class="mt-2">
                    <input type="text" class="form-control"
                           onClick="this.select();"
                           value="{{ $filteredRule->getRectorClass() }}">
                </div>

                <div class="row mt-3 mb-2">
                    <div class="col-12 filter-code-sample">
                            <pre><code
                                    class="language-diff">{{ $filteredRule->getDiffCodeSample() }}</code></pre>
                    </div>

                    @if ($filteredRule->getSets())
                        <div class="col-12 mb-1">
                            SETS:&nbsp;

                            @foreach ($filteredRule->getSets() as $set)
                                <span class="badge bg-danger">{{ $set }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <br>
    @endforeach

    <script>
        // Listen for events dispatched from Livewire components...
        document.addEventListener('DOMContentLoaded', function () {
            // render event from src/Livewire/RectorFilterComponent.php:12
            document.addEventListener('rules-filtered', () => {
                requestAnimationFrame(() => {
                    document.querySelectorAll('pre code.language-diff').forEach((element) => {
                        hljs.highlightElement(element);
                    });
                });
            });
        })
    </script>
</div>
