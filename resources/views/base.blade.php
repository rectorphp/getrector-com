<!DOCTYPE html>
<html lang="en">
    <!-- build timestamp: {{ date("Y-m-d, H:i:s") }} -->
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

        @include('_snippets/layout/head')
        @include('_snippets/styles')

        <link rel="icon" type="image/x-icon" href="favicon.ico" />

        {{-- font import --}}
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    </head>

    <body>
        @include('_snippets/menu')

        @if (request()->route()->action['controller'] === \App\Controller\HomepageController::class)
            {{-- special case for homepage, to allow full width white panels --}}
            @include('_snippets/flash_messages')

            @yield('main')
        @else
            <div class="container mobile-margin-top">
                @include('_snippets/flash_messages')

                @yield('main')
            </div>
        @endif

        @include('_snippets/layout/footer')

        @include('_snippets/javascripts')

        @isset ($livewireScripts)
            @livewireScripts
        @endisset

        <!-- 100% privacy-first analytics -->
        <script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
        <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt="" referrerpolicy="no-referrer-when-downgrade" /></noscript>
    </body>
</html>
