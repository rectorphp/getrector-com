<!DOCTYPE html>
<html lang="en">
    <!-- build timestamp: {{ date("Y-m-d, H:i:s") }} -->
    <head>
        @include('_snippets/layout/head')
        @include('_snippets/styles')

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    </head>

    <body>
        @include('_snippets/menu')

        <div class="container mobile-margin-top">
{{--                @include('_snippets/flash_messages')--}}

            @yield('main')
        </div>

        @include('_snippets/layout/footer')

        <script>
            ga=function(){ ga.q.push(arguments) };
            ga.q=[];
            ga.l=+new Date;
            ga('create', 'UA-46082345-2', 'auto');
            ga('send','pageview');
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>


        @include('_snippets/javascripts')
    </body>
</html>
