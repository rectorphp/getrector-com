@extends('base')

@php
    /** @var $customRuleRun \Rector\Website\Entity\CustomRuleRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form
            action="{{ action(\Rector\Website\Controller\Demo\ProcessDemoFormController::class) }}"
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

            <div style="float: right">
                Do you want to learn AST?
                Try new <a
                    href="{{ action(\Rector\Website\Controller\InteractiveController::class) }}">Learn
                    and Play</a> page
            </div>

            <p>
                Run to see how will your custom rule change PHP code:
            </p>

            @error('custom_rule')
            <div class="alert alert-danger">
                @foreach ($errors->get('custom_rule') as $error)
                    {{ $error }} <br/>
                @endforeach
            </div>
            @enderror

            <div class="card mb-4">
                <div class="card-header">Custom rule</div>

                <div class="card-body p-0">
                    <textarea name="custom_rule" class="codemirror_php"
                              required="required">{{ session('_old_input')['custom_rule'] ?? $customRuleRun->getRectorRule() }}</textarea>
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
                <div class="card-header">PHP snippet</div>

                <div class="card-body p-0 mb-0">
                    <textarea
                        name="php_contents" class="codemirror_php"
                        required="required">{{ session('_old_input')['php_contents'] ?? $customRuleRun->getContent() }}</textarea>
                </div>
            </div>

            @if ($customRuleRun->isSuccessful())
                <div class="card bg-warning border-warning mb-3">
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
