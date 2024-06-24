@extends('base')

@php
    /** @var $customRuleRun \Rector\Website\Entity\CustomRuleRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form
            action="{{ action(\Rector\Website\Controller\Demo\ProcessCustomRuleFormController::class) }}"
            method="post"
            class="mb-5"
        >
            @csrf <!-- {{ csrf_field() }} -->

            @if ($customRuleRun->hasRun() && $customRuleRun->isSuccessful() !== true)
                <div class="alert alert-danger mb-3">
                    <p>
                        <strong>Rector run Failed:</strong>
                    </p>

                    {!! $customRuleRun->getFatalErrorMessage() !!}

                    @if ($customRuleRun->getErrors() !== [])
                        <ul class="mt-3 ms-0 pl-4">
                            @foreach ($customRuleRun->getErrors() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @include('_snippets/demo/learn_ast_link')

            <p>
                Run to see how will your custom rule change PHP code:
            </p>

            @error('runnable_contents')
                <div class="alert alert-danger">
                    @foreach ($errors->get('runnable_contents') as $error)
                        {{ $error }} <br/>
                    @endforeach
                </div>
            @enderror

            <div class="card mb-4">
                <div class="card-header">Custom Rector
                    rule</div>

                <div class="card-body p-0">
                    <textarea name="runnable_contents" class="codemirror_php"
                              required="required">{{ session('_old_input')['runnable_contents'] ?? $customRuleRun->getRectorRule() }}</textarea>
                </div>
            </div>

            @error('php_contents')
            <div class="alert alert-danger">
                @foreach ($errors->get('php_contents') as $error)
                    {{ $error }} <br/>
                @endforeach
            </div>
            @enderror

            <div class="card mb-4">
                <div class="card-header">PHP snippet to change</div>

                <div class="card-body p-0 mb-0">
                    <textarea
                        name="php_contents" class="codemirror_php"
                        required="required">{{ session('_old_input')['php_contents'] ?? $customRuleRun->getContent() }}</textarea>
                </div>
            </div>

            @if ($customRuleRun->isSuccessful())
                <div class="card bg-success border-success mb-3">
                    <div class="card-header text-bold">
                        What did Rector change?
                    </div>

                    <div class="card-body p-0">
                        <textarea
                            class="codemirror_diff">{{ $customRuleRun->getContentDiff() }}</textarea>
                    </div>
                </div>
            @endif

            <button type="submit" id="demo_form_process" name="process"
                    class="btn btn-lg btn-success btn-demo-submit">Run Rector
            </button>
        </form>
    </div>
@endsection
