@extends('base')

@section('main')
    <div id="ast_run_form" class="mt-4 mb-3" style="min-height: 35em">
        <form
            action="{{ action(\Rector\Website\Controller\Ast\ProcessAstFormController::class) }}"
            method="post"
        >

            @csrf <!-- {{ csrf_field() }} -->

            <p>
                Fill in the PHP code to see its clickable abstract-syntax-tree:
            </p>

            @include('_snippets.form.form_textarea', [
                'label' => 'PHP snippet to turn into AST',
                'inputName' => 'php_contents',
                'defaultValue' => "<?php\n\necho 'hey';",
            ])

            <div class="row">
                <div class="col-6 mt-4 mb-5">
                    <button type="submit" id="ast_form_process" name="process"
                            class="btn btn-lg btn-success m-auto">Show AST
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
