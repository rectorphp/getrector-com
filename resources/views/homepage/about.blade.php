@extends('base')

@section('main')
    <div id="about">
        <h1 class="main-title">{{ $page_title }}</h1>

        <div class="text-medium">
            <p>
                Rector is a PHP tool that you can run on any PHP project to get an instant upgrade
                or automated refactoring.
            </p>

            <p>
                It helps yous especially with:

            <ul>
                <li>PHP upgrades,</li>
                <li>framework upgrades,</li>
                <li>improving your code quality</li>
                <li>it gives you safety and light-speed</li>
            </ul>
            </p>

            <p>
                The best way to learn it is <a
                        href="{{ action(\Rector\Website\Controller\DocumentationController::class) }}">documentation</a>,
                followed by in-depth book <a
                        href="https://leanpub.com/rector-the-power-of-automated-refactoring">The
                    Power
                    of Automated Refactoring</a>.
            </p>
        </div>

        <div class="text-medium mt-5">
            <p>
                In the hands of an expert, Rector massively reduces your work-time. In a project
                where upgrade of PHP 8.0 to 8.3 <strong>would take from 3 months, Rector will help
                    you achieve same in 3 days</strong>.
            </p>

            <p>
                It's intuitive and 100 % reliable, because it looks for narrowly defined patterns.
            </p>

            <br>

            <p>
                <a href="{{ action(\Rector\Website\Controller\HireTeamController::class) }}">Hire
                    our upgrade team</a> to get this expertise integrated into your team instantly.
            </p>
        </div>

    </div>
@endsection
