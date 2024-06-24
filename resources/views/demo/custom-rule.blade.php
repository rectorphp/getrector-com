@extends('base')

@php
    /** @var $rectorRun \App\Entity\CustomRuleRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form
            action="{{ action(\App\Controller\Demo\ProcessCustomRuleFormController::class) }}"
            method="post"
            class="mb-5"
        >
            @csrf <!-- {{ csrf_field() }} -->

            @include('_snippets/form/form_fatal_errors')

            @include('_snippets/demo/learn_ast_link')

            <p>
                Create custom Rector rule and see how it works:
            </p>

            @include('_snippets.form.form_textarea', [
                'label' => 'Custom Rector rule',
                'inputName' => 'runnable_contents',
                'defaultValue' => $rectorRun->getRunnablePhp()
            ])

            @include('_snippets.form.form_textarea', [
                'label' => 'PHP snippet to change',
                'inputName' => 'php_contents',
                'defaultValue' => $rectorRun->getContent()
            ])

            @if ($rectorRun->isSuccessful())
                <div class="card bg-success border-success mb-3">
                    <div class="card-header text-bold text-white">
                        What did Rector change?
                    </div>

                    <div class="card-body p-0">
                        <textarea
                            class="codemirror_diff">{{ $rectorRun->getContentDiff() }}</textarea>
                    </div>
                </div>
            @endif

            <button type="submit" id="demo_form_process" name="process"
                    class="btn btn-lg btn-success btn-demo-submit mt-3">Run Rector
            </button>
        </form>
    </div>
@endsection
