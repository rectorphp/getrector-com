@extends('base')

@php
    /** @var $ast_run \Rector\Website\Entity\AstRun */
@endphp

@section('main')
    <div id="ast_run_form" class="mt-4 mb-3">
        <div class="row">
            <div class="col-6">
                <form
                    action="{{ action(\Rector\Website\Http\Controller\Ast\ProcessAstFormController::class) }}"
                    method="post">

                    @csrf <!-- {{ csrf_field() }} -->

                    <p>
                        See the real AST of your code:
                    </p>

                    @error('php_contents')
                    <div class="alert alert-danger">
                        @foreach ($errors->get('php_contents') as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                    @enderror

                    <div class="card mb-4">
                        <div class="card-body p-0 mb-0">
                            <textarea name="php_contents" class="codemirror_php" required>{{ session('_old_input')['php_contents'] ?? "<?php\n\n" }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-4 mb-5">
                            <button type="submit" id="ast_form_process" name="process"
                                    class="btn btn-lg btn-success m-auto">Show me
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
