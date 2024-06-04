@php
    /** @var \Rector\Website\RuleFilter\ValueObject\RuleMetadata[] $filteredRules */
@endphp

<div>
    <input type="text" class="form-control d-inline" style="width: 15em"  wire:model.live="query">

    {{ count($filteredRules) }}


</div>
