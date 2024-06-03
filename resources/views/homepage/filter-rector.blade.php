@extends('base')

@php
    /** @var \Rector\Website\ValueObject\RichRuleDefinition[] $coreRectorRules */
@endphp

@section('main')
    <style>
        .filter-code-sample {
            font-size: .9em;
        }
    </style>

    <div id="filter-rector" class="mt-4">
        <div class="row">
            <div class="col-4">
                <form
                    action="{{ action(\Rector\Website\Controller\FilterRectorController::class) }}"
                    method="GET">
                    <div class="form-field">
                        <input
                            name="query"
                            class="form-control d-inline"
                            placeholder="Search for a rule"
                            value="{{ request('query') }}"
                        >

                        @error('name')
                        <div>{{ $message }}</div>
                        @enderror

                        <button type="submit" id="ast_form_process" name="process"
                                class="btn btn-success d-inline mt-2">Filter (will be removed and
                            replaced by livewire)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($coreRectorRules as $coreRectorRule)
            <div class="mb-3 pt-3">
                <h3 class="mb-4s">{{ $coreRectorRule->getRuleShortClass() }} </h3>

                <div class="mt-2">
                    <input type="text" class="form-control"
                           value="{{ $coreRectorRule->getRuleClass() }}">
                </div>
            </div>

        <div class="row">
            <div class="col-8 filter-code-sample">
                <pre><code class="language-diff">{{ $coreRectorRule->getDiffCodeSample() }}</code></pre>
            </div>

            <div class="col-4">
                @if ($coreRectorRule->isConfigurable())
                    <div class="mb-3">
                        <span class="badge bg-primary">Configurable</span>
                    </div>
                @endif

                @if ($coreRectorRule->getSets())
                    SETS:&nbsp;

                    @foreach ($coreRectorRule->getSets() as $set)
                        <span class="badge bg-danger">{{ $set }}</span>
                    @endforeach
                @endif
            </div>
        </div>

        <br>
        @endforeach

        <br>
    </div>
@endsection
