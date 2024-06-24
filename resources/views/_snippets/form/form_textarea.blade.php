@php
    /** @var string $label */
    /** @var string $inputName */
    /** @var string $defaultValue */
@endphp

@error($inputName)
    <div class="alert alert-danger">
        @foreach ($errors->get($inputName) as $error)
            {{ $error }} <br/>
        @endforeach
    </div>
@enderror

<div class="card mb-4">
    <div class="card-header">{!! $label !!}</div>

    <div class="card-body p-0">
        <textarea
            name="{{ $inputName }}"
            class="codemirror_php"
            required="required">{{ session('_old_input')[$inputName] ?? $defaultValue }}</textarea>
    </div>
</div>
