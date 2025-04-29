<!DOCTYPE html>
<html lang="en">
    <!-- build timestamp: {{ date("Y-m-d, H:i:s") }} -->
    <head>
        @include('_snippets/layout/head')
        @include('_snippets/styles')

        <link rel="icon" type="image/x-icon" href="favicon.ico" />

        {{-- font import --}}
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    </head>

    <body>
        @include('_snippets/menu')

        <div class="container mobile-margin-top">
            @include('_snippets/flash_messages')

            @yield('main')
        </div>

        @include('_snippets/layout/footer')

        @include('_snippets/javascripts')

        @livewireScripts

        <!-- 100% privacy-first analytics -->
        <script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
        <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt="" referrerpolicy="no-referrer-when-downgrade" /></noscript>
    </body>
</html>
