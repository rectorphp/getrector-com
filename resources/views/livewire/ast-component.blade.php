<span>
    <div class="col-12 col-md-6" style="float:right">
        <p class="mt-3">
            <span class="headline-kick">2. Click</span> on any part of the code
            @if ($nodeId)
                (<a href="#" wire:click="$dispatch('select_node', {nodeId: null})">show full tree</a>)
            @endif
        </p>

        <div id="clickable-nodes-code" class="mb-4">
            <pre><code class="hljs"  style="min-height: 19.6em">&lt;?php<br/><br/>{!! $matrixVision !!}</code></pre>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-12">
        <p class="mt-3">
            <span class="headline-kick">3. See</span>
            Abstract Syntax Tree created by php-parser for

            @if ($nodeId)
                selected node:
            @else
                full file
            @endif
        </p>

        <pre><code class="language-php" style="min-height: 10em">{{ $simpleNodeDump }}</code></pre>
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
        });
    </script>
</span>
