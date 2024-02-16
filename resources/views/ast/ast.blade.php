@extends('base')

@php
    /** @var $rector_run \Rector\Website\Entity\RectorRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form action="{{ action(\Rector\Website\Http\Controllers\ProcessAstFormController::class) }}"
              method="post">

            @csrf <!-- {{ csrf_field() }} -->

{{--            @if ($rector_run->hasRun() && $rector_run->isSuccessful() !== true)--}}
{{--                <div class="alert alert-danger mb-3">--}}
{{--                    <p>--}}
{{--                        <strong>Rector run Failed:</strong>--}}
{{--                    </p>--}}

{{--                    {!! $rector_run->getFatalErrorMessage() !!}--}}

{{--                    @if ($rector_run->getErrors() !== [])--}}
{{--                        <ul class="mt-3 ms-0 pl-4">--}}
{{--                            @foreach ($rector_run->getErrors() as $error)--}}
{{--                                <li>--}}
{{--                                    {{ $error }}--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            @endif--}}

            <p>
                Run Rector on your code to see what it can do for you:
            </p>

            @error('php_contents')
            <div class="alert alert-danger">
                @foreach ($errors->get('php_contents') as $error)
                    {{ $error }}
                @endforeach
            </div>
            @enderror

{{--            <div class="card mb-4">--}}
{{--                <div class="card-body p-0 mb-0">--}}
{{--                    <textarea name="php_contents" class="codemirror_php"--}}
{{--                        required="required">{{ session('_old_input')['php_contents'] ?? $rector_run->getContent() }}</textarea>--}}
{{--                </div>--}}
{{--            </div>--}}


            <div class="row">
                <div class="col-6 mt-4 mb-5">
                    <button type="submit" id="demo_form_process" name="process"
                            class="btn btn-lg btn-success m-auto btn-demo-submit">Process
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
