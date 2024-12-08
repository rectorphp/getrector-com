@extends('base')

@php
    /** @var \App\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
@endphp

@section('main')
    <div id="filter" class="mt-4" style="min-height: 30em">
        <p class="mb-4">
            Find the best Rector rule to solve your problem. Searching through {{ $ruleCount }} rules.
        </p>

        @livewire('find-rule-component')
    </div>
@endsection
