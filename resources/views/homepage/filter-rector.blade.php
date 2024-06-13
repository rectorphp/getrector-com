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

    <div id="filter-rector" class="mt-4" style="min-height: 30em">
        <p class="mb-3">
            Find the best Rector rule to solve your problem
        </p>

        @livewire('rector-filter-component')
    </div>
@endsection
