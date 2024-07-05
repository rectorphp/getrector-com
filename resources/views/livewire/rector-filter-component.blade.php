@php
    /** @var \App\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
    /** @var \App\RuleFilter\ValueObject\RectorSet[] $rectorSets */
    /** @var array<string, string> $nodeTypeSelectOptions */
@endphp

<div>
    <div class="row">
        <div class="col-12 col-md-4 mb-3">
            <input
                placeholder="Type to start searching a rule"
                type="text"
                class="form-control d-inline"
                style="width: 14em"
                wire:model.live.debounce.300ms="query"
            >
            <!-- @see https://livewire.laravel.com/docs/wire-model#customizing-the-debounce -->

            @if ($isFilterActive)
                <a href="{{ action(\App\Controller\FilterRectorController::class) }}" class="ms-2">Clear</a>
            @endif

        </div>
        <div class="col-12 col-md-5 mb-3">
            <label for="node_type">Element to change:</label>

            <select class="form-select d-inline ms-3" name="node_type" style="max-width: 12em" wire:model.live="nodeType">
                <option value="">Any element</option>

                @foreach (\App\Enum\NodeTypeToHumanReadable::SELECT_ITEMS_BY_GROUP as $groupName => $nodeTypesToNames)
                    <optgroup label="{{ $groupName }}">
                        @foreach ($nodeTypesToNames as $optionValue => $optionName)
                            <option value="{{ $optionValue }}">{{ $optionName }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <label for="node_type">Set:</label>

            <select class="form-select d-inline ms-3" name="set" style="max-width: 11.3em" wire:model.live="set">
                <option value="">Any set</option>

                @foreach ($rectorSetsByGroup as $groupName => $rectorSets)
                    <optgroup label="{{ $groupName }}">

                    @foreach ($rectorSets as $rectorSet)
                        <option value="{{ $rectorSet->getName() }}">
                            {{ $rectorSet->getHumanName() }}
                            ({{ $rectorSet->getRuleCount() }})
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>

    <div class="clearfix mb-2"></div>

    <br>

    @if ($isFilterActive)
        @foreach ($filteredRules as $filteredRule)
            <div style="--bs-border-opacity: .5;" class="mb-3 mt-2 card
                    @if ($filteredRule->isConfigurable())
                        border-primary
                    @endif
                "
            >
                <div class="card-body pt-0 pb-3 ps-4 pe-4">
                    <div style="float: right" class="mt-4">
<<<<<<< HEAD
                        <a href="{{ action(\App\Controller\RuleDetailController::class, ['slug' => $filteredRule->getSlug()]) }}">Rule detail</a>
=======
                        <a href="{{ action(\App\Controller\RuleDetailController::class, ['slug' => $filteredRule->getSlug()]) }}" class="btn btn-success">Rule detail</a>
>>>>>>> 3a2330b (spacing)

                        @if ($filteredRule->isConfigurable())
                            <span class="ms-3 mt-4">
                                <span class="badge bg-primary">Configurable</span>
                            </span>
                        @endif

                        @if ($filteredRule->getFilterScore())
                            <small class="text-secondary ms-2">
                                Score: {{ $filteredRule->getFilterScore() }}
                            </small>
                        @endif
                    </div>

                    <h3 class="mb-4">{{ $filteredRule->getRuleShortClass() }}</h3>

                    <p>{!! $filteredRule->getDescription() !!}</p>

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

        @if ($filteredRules === [])
            <p>No rules found. Try different query.</p>
        @endif
    @else
        <p>Not sure how to search? Try one of these for a start:</p>

        <ul>
            @foreach ($queryExamples as $queryExample)
                <li>
                    <a href="?query={{ $queryExample }}">{{ $queryExample }}</a>
                </li>
          @endforeach
        </ul>
    @endif

    <script>
        // Listen for events dispatched from Livewire components...
        document.addEventListener('DOMContentLoaded', function () {
            // render event from src/Livewire/RectorFilterComponent.php:12
            document.addEventListener('{{ \App\Enum\ComponentEvent::RULES_FILTERED }}', () => {
                requestAnimationFrame(() => {
                    document.querySelectorAll('pre code.language-diff').forEach((element) => {
                        hljs.highlightElement(element);
                    });
                });
            });
        })
    </script>
</div>
