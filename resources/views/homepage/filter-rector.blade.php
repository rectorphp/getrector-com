@extends('base')

@php
    /** @var \Rector\Website\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
@endphp

@section('main')
    <div id="filter" class="mt-4" style="min-height: 30em">
        <p class="mb-3">
            Find the best Rector rule to solve your problem
        </p>

        @livewire('rector-filter-component')
    </div>
@endsection
