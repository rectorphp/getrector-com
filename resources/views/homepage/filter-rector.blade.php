@extends('base')

@php
    /** @var \Rector\Website\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
@endphp

@section('main')
    <style>
        .filter-code-sample {
            font-size: .9em;
        }
    </style>

    <div id="filter-rector" class="mt-4">
        @livewire('rector-filter-component')

        <hr>

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
                                class="btn btn-success d-inline mt-2">Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

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

        <br>
    </div>
@endsection
