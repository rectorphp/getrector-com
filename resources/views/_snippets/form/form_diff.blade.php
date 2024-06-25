<style>
    pre code {
        font-size: .9em;
        line-height: 1.28em;
    }
</style>

@if ($rectorRun->isSuccessful())
    <div class="col-6" style="float: right;" class="diff-snippet">
        @foreach ($rectorRun->getDiffSnippets() as $diffSnippet)
            <pre style="position: absolute; z-index: 10; width: 32.7em; margin-top: {{ $diffSnippet->getLine() * 1.4 + 1.7 }}em"><code class="language-diff">{{ $diffSnippet->getSnippet() }}</code></pre>
        @endforeach
    </div>
@endif
