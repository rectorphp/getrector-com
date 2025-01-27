@extends('base')

@section('main')
    <style>
        .headline-kick {
            font-size: 1.4em;
            padding-right: .2em;
            font-weight: 500;
            font-family: Inter, sans-serif;
        }
    </style>

    <div class="mt-2 mb-3" style="min-height: 50em" id="simple_page">
        <div class="col-12 col-md-6 pe-4" style="float:left; position: relative">
            <form
                action="{{ action(\App\Ast\Controller\ProcessAstFormController::class) }}"
                method="post"
            >
                @csrf

                <p class="mt-3">
                    <span class="headline-kick">1. Write</span>
                    short PHP code you want to understand
                </p>

                <div style="right: 3em; top: 17.5em; z-index: 100; position: absolute">
                    <button type="submit" id="ast_form_process" name="process" class="btn btn-success">
                        Parse
                    </button>
                </div>

                @include('_snippets.form.form_textarea', [
                    'label' => null,
                    'inputName' => 'php_contents',
                    'defaultValue' => $inputFormContents,
                ])
            </form>
        </div>

        @if ($astRun instanceof \App\Ast\Entity\AstRun)
            @livewire('ast-component', ['astRun' => $astRun, 'inputFormContents' => $inputFormContents])
        @endif
    </div>

    <br>
    <br>
@endsection
