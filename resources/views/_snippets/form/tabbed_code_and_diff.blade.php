<style>
    .code-editor-tabs {
        display: flex;
        gap: 1px;
        background: #dee2e6;
        padding: 1px;
        border-radius: 6px 6px 0 0;
    }

    .code-editor-tab {
        padding: 8px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px 5px 0 0;
        flex: 1;
        max-width: 150px;
    }

    .code-editor-tab.active { background: #495057; color: white; }
    .code-editor-tab:hover:not(.active) { background: #f8f9fa; }

    .code-editor-content-container {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 6px 6px;
        min-height: 400px;
    }

    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    #diff-content {
        padding: 1em;
        background: #f8f9fa;
    }

    #diff-content pre { margin: 0; background: transparent; border: none; }

    #diff-content code {
        display: block;
        padding: 1em;
        border-radius: 4px;
        font-size: 0.875em;
        line-height: 1.45;
        white-space: pre;
        overflow-x: auto;
    }

    .diff-separator {
        text-align: center;
        font-size: 0.85em;
    }
</style>

@php
    /** @var $rectorRun \App\Entity\RectorRun */
    /** @var string $inputName */
@endphp

@error($inputName)
    <div class="alert alert-danger">
        @foreach ($errors->get($inputName) as $error)
            {{ $error }} <br/>
        @endforeach
    </div>
@enderror

<div class="code-editor-container mb-4" id="code-editor-container">
    <div class="code-editor-tabs">
        <button type="button" class="code-editor-tab" id="code-tab" title="View and edit code (Ctrl+1)">Code</button>
        <button type="button" class="code-editor-tab active" id="diff-tab" title="View changes (Ctrl+2)">Diff</button>
    </div>

    <div class="code-editor-content-container">
        <div class="tab-pane" id="code-content">
            <textarea
                name="{{ $inputName }}"
                class="codemirror_php"
                required="required">{{ session('_old_input')[$inputName] ?? $rectorRun->getContent() }}</textarea>
        </div>

        <div class="tab-pane active" id="diff-content">
            @foreach ($rectorRun->getDiffSnippets() as $index => $diffSnippet)
                <pre><code class="language-diff">{{ $diffSnippet->getSnippet() }}</code></pre>
                @if ($index < count($rectorRun->getDiffSnippets()) - 1)
                    <div class="diff-separator">• • •</div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const codeTab = document.getElementById("code-tab");
        const diffTab = document.getElementById("diff-tab");
        const codeContent = document.getElementById("code-content");
        const diffContent = document.getElementById("diff-content");

        let codeTabRefreshed = false;

        function switchTab(tab) {
            if (tab === "code") {
                codeTab.classList.add("active");
                diffTab.classList.remove("active");
                codeContent.classList.add("active");
                diffContent.classList.remove("active");

                if (!codeTabRefreshed) {
                    codeTabRefreshed = true;
                    codeContent.querySelector(".CodeMirror")?.CodeMirror.refresh();
                }
            } else {
                codeTab.classList.remove("active");
                diffTab.classList.add("active");
                codeContent.classList.remove("active");
                diffContent.classList.add("active");
            }
        }

        codeTab.addEventListener("click", () => switchTab("code"));
        diffTab.addEventListener("click", () => switchTab("diff"));

        document.addEventListener("keydown", function (e) {
            if (!e.ctrlKey && !e.metaKey) {
                return;
            }
            if (e.key === "1") {
                e.preventDefault();
                switchTab("code");
            } else if (e.key === "2") {
                e.preventDefault();
                switchTab("diff");
            }
        });
    });
</script>
