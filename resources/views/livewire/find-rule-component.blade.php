@php
    /** @var \App\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
    /** @var \App\RuleFilter\ValueObject\RectorSet[] $rectorSets */
    /** @var array<string, string> $nodeTypeSelectOptions */
@endphp

<div>
    <div class="row">
        <div class="col-12 col-md-5 mb-4">
            <input
                placeholder="Type to start searching a rule"
                type="text"
                class="form-control d-inline"
                style="width: 18em"
                wire:model.live.debounce.300ms="query"
                autofocus="true"
            >
            <!-- @see https://livewire.laravel.com/docs/wire-model#customizing-the-debounce -->

            @if ($isFilterActive)
                <a href="{{ action(\App\Controller\FindRuleController::class) }}" class="ms-2">Clear</a>
            @endif
        </div>

        <div class="col-12 col-md-3 d-flex">
            <label for="set_group" class="pt-2">Group:</label>

            <select
                class="form-select d-inline ms-2" name="set_group"
                style="height: 2.4em; width: 11.5em"
                wire:model.live="activeRectorSetGroup">
            >

            @foreach ($rectorSetGroups as $groupSlug => $groupName)
                <option value="{{ $groupSlug }}">
                    {{ $groupName }}
                </option>
            @endforeach
            </select>
        </div>

        <div class="col-12 col-md-4 ps-5 d-flex">
            <label for="rector_set" class="pt-2">Set:</label>

            <select
                class="form-select ms-3"
                name="rector_set"
                style="height: 2.4em"
                wire:model.live="rectorSet"
                @if (! $activeRectorSetGroup)
                    disabled
                @endif
            >
                @if (! $activeRectorSetGroup)
                    <option value="">Pick group to filter</option>
                @else
                    <option value="">All sets</option>

                    @foreach ($rectorSets as $rectorSet)
                        <option value="{{ $rectorSet->getSlug() }}">
                            {{ $rectorSet->getName() }}
                            ({{ $rectorSet->getRuleCount() }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="clearfix mb-2"></div>

    @if ($isFilterActive)
        <p>
            @if ($filteredRules === 0)
                No rule found. Try changing group to more generic one, or use different query.
            @elseif (count($filteredRules) === 1)
                Found 1 rule. That's the one:
            @else
                Found <strong>{{ count($filteredRules) }} rules</strong>:
            @endif
        </p>

        @foreach ($filteredRules as $filteredRule)
            <div style="--bs-border-opacity: .5;" class="mb-3 mt-2 card
                    @if ($filteredRule->isConfigurable())
                        border-primary
                    @endif
                "
            >
                <div class="card-body pt-0 pb-3 ps-4 pe-4">
                    <div style="float: right" class="mt-4">
                        @if ($filteredRule->isConfigurable())
                            <span class="ms-3 mt-4">
                                <span class="badge bg-primary">Configurable</span>
                            </span>
                        @endif

                        @if ($filteredRule->getFilterScore() && app('env') === 'dev')
                            <small class="text-secondary ms-2">
                                Score: {{ $filteredRule->getFilterScore() }}
                            </small>
                        @endif
                    </div>

                    <h3 class="mb-4">
                        <a href="{{ action(\App\Controller\RuleDetailController::class, ['slug' => $filteredRule->getSlug()]) }}">
                            {{ $filteredRule->getRuleShortClass() }}
                        </a>
                    </h3>

                    <p>{!! $filteredRule->getDescription() !!}</p>

                    <div class="row mt-3 mb-2">
                        <div class="col-12 filter-code-sample">
                            <pre><code
                                        class="language-diff">{{ $filteredRule->getDiffCodeSample() }}</code></pre>
                        </div>

                        @if ($filteredRule->isPartOfSets())
                            <div class="col-12 mb-1">
                                SETS:&nbsp;

                                @foreach ($filteredRule->getSets() as $rectorSet)
                                    <a href="{{ action(\App\Controller\FindRuleController::class, [
                                        'rectorSet' => $rectorSet->getSlug(),
                                        'activeRectorSetGroup' => $rectorSet->getGroupName()
                                    ]) }}"><span class="badge bg-set">{{ $rectorSet->getName()}}</span></a>
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
        <br>

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
