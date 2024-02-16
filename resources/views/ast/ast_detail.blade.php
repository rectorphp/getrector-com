@extends('base')

@php
    /** @var $ast_run \Rector\Website\Entity\AstRun */
@endphp

@section('main')
    <div class="float-end mt-4">
        <a href="{{ action(\Rector\Website\Http\Controller\Ast\AstController::class) }}" class="btn btn-outline-success">
            ← Create new code
        </a>
    </div>

    <div class="mt-4 mb-5" style="min-height: 30em">
        <p>Click on code part to see its AST</p>

        <code>
                {!! $matrix_vision !!}
        </code>

        <br>
        <br>
        <br>

        <div class="row">
            <div class="col-12">
                <pre><code class="language-php">{{ $simple_node_dump }}</code></pre>
            </div>
        </div>

        <br>
    </div>
@endsection
