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
        @livewire('rector-filter-component')
    </div>
@endsection
