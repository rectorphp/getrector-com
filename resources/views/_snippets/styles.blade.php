<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600&display=swap" rel="stylesheet">

{{-- live code highligh in demo --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/theme/solarized.min.css">

{{-- code highligh posts --}}
{{-- pick from https://highlightjs.org/demo --}}
{{-- see ChatGPT https://chat.openai.com/share/af70716e-067c-481c-ad61-fc40de2f4dc3 --}}


<link rel="stylesheet" id="theme-link" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/base16/atlas.min.css">
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/base16/atelier-dune-light.min.css">--}}

<style>
    pre code.hljs {
        border-radius: .6em;
    }

    /* only for light theme */
    .hljs {
        border: 1px solid #d8d2af;
    }
</style>


<script>
    // Function to load theme from local storage
    function loadTheme() {
        const theme = localStorage.getItem('theme');
        if (theme) {
            document.getElementById('theme-link').setAttribute('href', theme);
        }

        const theme_toggle_text = localStorage.getItem('theme_toggle_text');
        if (theme_toggle_text) {
            document.getElementById('theme_toggle').textContent = theme_toggle_text;
        }
    }

    // Function to toggle theme and save preference to local storage
    function toggleTheme() {
        var theme = document.getElementById('theme-link');
        var theme_toggle = document.getElementById('theme_toggle');
        var lightTheme = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/base16/atelier-dune-light.min.css';
        var darkTheme = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/base16/atlas.min.css';

        if (theme.getAttribute('href') === darkTheme) {
            theme.setAttribute('href', lightTheme);
            localStorage.setItem('theme', lightTheme);
            localStorage.setItem('theme_toggle_text', 'Dark Code Theme');

            theme_toggle.textContent = 'Dark Code Theme';
        } else {
            theme.setAttribute('href', darkTheme);
            localStorage.setItem('theme', darkTheme);
            localStorage.setItem('theme_toggle_text', 'Light Code Theme');

            theme_toggle.textContent = 'Light Code Theme';
        }
    }

    // Load the theme when the page loads
    window.onload = loadTheme;
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/yaml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/diff.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/json.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre code.language-bash, pre code.language-php, pre code.language-yaml, pre code.language-diff, pre code.language-json').forEach((element) => {
            hljs.highlightElement(element);
        });
    });
</script>

@vite(['resources/css/app.scss', 'resources/js/app.js'])
