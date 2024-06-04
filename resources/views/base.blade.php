<!DOCTYPE html>
<html lang="en">
    <!-- build timestamp: {{ date("Y-m-d, H:i:s") }} -->
    <head>
        @include('_snippets/layout/head')
        @include('_snippets/styles')

        <link rel="icon" type="image/x-icon" href="favicon.ico" />

    </head>

    <body>
        @include('_snippets/menu')

        <div class="container mobile-margin-top">
            @include('_snippets/flash_messages')

            @yield('main')
        </div>

        @include('_snippets/layout/footer')

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-46082345-2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-46082345-2');
        </script>

        @include('_snippets/javascripts')

        @livewireScripts

        <script>
            // Listen for events dispatched from Livewire components...
            Livewire.on('rules-filtered', () => {
                document.querySelectorAll('pre code.language-diff').forEach((block) => {
                    hljs.highlightBlock(block);
                });

                // alert('555');
            })
        </script>
    </body>
</html>
