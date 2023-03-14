@extends('base')

@section('main')
    <div id="about">
        <h1 class="main-title">{{ $page_title }}</h1>

        <div class="text-medium">
            <p>
                Rector is a PHP tool that you can run on any PHP project to get an instant upgrade or automated refactoring. It helps with PHP upgrades, framework upgrades and improves your code quality. Also, it helps with type-coverage and getting to the latest PHPStan level.
            </p>

            <p>
                You can learn about it from the <a href="{{ action(\App\Http\Controllers\DocumentationController::class) }}">documentation</a> or
                from our book <a href="https://leanpub.com/rector-the-power-of-automated-refactoring">Rector - The Power of Automated Refactoring</a>.
            </p>
        </div>

        <div class="text-medium mt-5">
            <p>
                In the hands of an expert, Rector achieves massive changes in minutes that would take typical team couple of months. It's very reliable because it looks for narrowly defined AST patterns.
            </p>

            <p>
                You can <a href="{{ action(\App\Http\Controllers\ForCompaniesController::class) }}">hire our team</a> to get this expertise integrated into your team instantly.
            </p>
        </div>

    </div>
@endsection
