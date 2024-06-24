<div>
    <div class="float-end mt-0">
        <a href="{{ action(\App\Ast\Controller\AstController::class) }}"
           class="btn btn-outline-success" style="margin-top: -.7em">
            ← Create new code
        </a>
    </div>

    <div class="mt-4 mb-5" style="min-height: 30em">
        <p>Click on code part to see its AST</p>

        <div id="clickable-nodes-code" class="mb-4">
            <pre><code class="hljs">&lt;?php<br/><br/>{!! $matrixVision !!}</code></pre>
        </div>

        <br>

        <p>Selected code is represented by following abstract syntax tree:</p>

        <div class="row">
            <div class="col-12">
                <pre><code class="language-php">{{ $simpleNodeDump }}</code></pre>
            </div>
        </div>

        <br>

        @if ($targetNodeClass)
            <p>
                What class-string to put into <code>Rector::getNodeTypes()</code> method to hook
                into?
            </p>

            <pre><code class="language-php">\{{ $targetNodeClass }}::class</code></pre>
        @endif

        <br>
    </div>

    <script>
        // Listen for events dispatched from Livewire components...
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('{{ \App\Enum\ComponentEvent::NODE_SELECTED }}', () => {
                requestAnimationFrame(() => {
                    document.querySelectorAll('pre code.language-php').forEach((element) => {
                        hljs.highlightElement(element);
                    });
                });
            });
        })
    </script>
</div>
