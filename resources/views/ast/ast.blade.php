@extends('base')

@section('main')
    <div id="ast_run_form" class="mt-2 mb-3" style="min-height: 35em">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3 class="mb-3">1. Write PHP code</h3>

                <p>
                    Write PHP code you're interested to see in AST
                </p>

                <form
                    action="{{ action(\App\Ast\Controller\ProcessAstFormController::class) }}"
                    method="post"
                >

                    @csrf <!-- {{ csrf_field() }} -->

                    @include('_snippets.form.form_textarea', [
                        'label' => null,
                        'inputName' => 'php_contents',
                        'defaultValue' => $inputFormContents,
                    ])

                    <div class="mb-5 mt-0 pt-0">
                        <button type="submit" id="ast_form_process" name="process"
                                class="btn w-100 btn-lg btn-success m-auto">Show AST &nbsp;&nbsp; 👉
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-md-6">
                <h3 class="mb-3">2. Click</h3>

                @isset ($astRun)
                    <p>Click on any part of the code</p>

                    @livewire('ast-component', ['astRun' => $astRun])
                @endisset
            </div>
        </div>
    </div>
@endsection
