@extends('base')

@php
    /** @var $ast_run \Rector\Website\Entity\AstRun */
@endphp

@section('main')
    <div class="float-end mt-4">
        <a href="{{ action(\Rector\Website\Http\Controller\Ast\AstController::class) }}" class="btn btn-outline-success" style="margin-top: -.7em">
            ← Create new code
        </a>
    </div>

    <div class="mt-4 mb-5" style="min-height: 30em">
        <p>Click on code part to see its AST</p>

        <div id="clickable-nodes-code" class="mb-4">
            <pre><code class="hljs">&lt;?php

{!! $matrix_vision !!}</code></pre>

        </div>

        <p>Selected code is represented by following abstract syntax tree:</p>

        <div class="row">
            <div class="col-12">
                <pre><code class="language-php">{{ $simple_node_dump }}</code></pre>
            </div>
        </div>

        <br>

        @if ($target_node_class)
            <p>
                What node you hook into to modify it? In <code>Rector::getNodeTypes()</code> method.
            </p>

            <pre><code class="language-php">{{ $target_node_class }}</code></pre>
        @endif

        <br>
    </div>
@endsection
