@extends('base')

@php
    /** @var $ast_run \Rector\Website\Entity\AstRun */
@endphp

@section('main')
    <div class="mt-4 mb-5">
        <p>Click on code part to see its AST</p>

        <div class="row">
            <div class="col-4">
                <code>
                    {!! $matrix_vision !!}
                </code>
            </div>

            <div class="col-8">
                <pre><code class="language-php">{{ $simple_node_dump }}</code></pre>
            </div>
        </div>

        <br>

        <div>
            <a href="{{ action(\Rector\Website\Http\Controller\Ast\AstController::class) }}" class="btn btn-outline-success">
                ← Create new code
            </a>
        </div>
    </div>
@endsection
