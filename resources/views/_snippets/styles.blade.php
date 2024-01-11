<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600&display=swap" rel="stylesheet">

{{-- live code highligh in demo --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/theme/solarized.min.css">

{{-- code highligh posts --}}
{{-- pick from https://highlightjs.org/demo --}}
{{-- see ChatGPT https://chat.openai.com/share/af70716e-067c-481c-ad61-fc40de2f4dc3 --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/base16/atlas.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/yaml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/diff.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre code.language-php').forEach((block) => {
            hljs.highlightBlock(block);
        });

        document.querySelectorAll('pre code.language-yaml').forEach((block) => {
            hljs.highlightBlock(block);
        });

        document.querySelectorAll('pre code.language-bash').forEach((block) => {
            hljs.highlightBlock(block);
        });

        document.querySelectorAll('pre code.language-diff').forEach((block) => {
            hljs.highlightBlock(block);
        });
    });
</script>

@vite(['resources/css/app.scss', 'resources/js/app.js'])
