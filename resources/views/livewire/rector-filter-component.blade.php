@php
    /** @var \Rector\Website\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
@endphp

<div>
    <input
        placeholder="Search for a rule"
        type="text"
        class="form-control d-inline"
        style="width: 15em"
        wire:model.live.debounce.300ms="query"
    >
    <!-- @see https://livewire.laravel.com/docs/wire-model#customizing-the-debounce -->

    @foreach ($filteredRules as $filteredRule)
        <div class="mb-3 pt-3">
            <h3 class="mb-4s">{{ $filteredRule->getRuleShortClass() }} </h3>

            <div class="mt-2">
                <input type="text" class="form-control"
                       value="{{ $filteredRule->getRuleClass() }}">
            </div>
        </div>

        <div class="row">
            <div class="col-8 filter-code-sample">
                    <pre><code
                            class="language-diff">{{ $filteredRule->getDiffCodeSample() }}</code></pre>
            </div>

            <div class="col-4">
                @if ($filteredRule->isConfigurable())
                    <div class="mb-3">
                        <span class="badge bg-primary">Configurable</span>
                    </div>
                @endif

                @if ($filteredRule->getSets())
                    SETS:&nbsp;

                    @foreach ($filteredRule->getSets() as $set)
                        <span class="badge bg-danger">{{ $set }}</span>
                    @endforeach
                @endif
            </div>
        </div>



        <br>
    @endforeach
</div>
