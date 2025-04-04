@extends('base')

@section('main')
    <div id="homepage">
        <h1 class="main-title">
            The Way You Can Finally
            <br>
            <span class="text-rector-green">Upgrade PHP</span>
        </h1>

        <div class="text-bigger text-center">
            <p>We help successful and growing companies to get the most of the code they already
                have.</p>

            <p>
                <strong>Reduce maintenance cost</strong>, <strong>make feature delivery
                    cheaper</strong><br>
                and turn legacy code into sustainable code.
            </p>
        </div>

        <div class="text-center mt-5 mb-5 bigger-buttons">
            <a href="{{ action(\App\Controller\HireTeamController::class) }}"
               class="btn btn-success me-3">Hire Upgrade Team</a>
            <a href="{{ action(\App\Controller\Demo\DemoController::class) }}"
               class="btn btn-primary">Try It Online</a>
        </div>

        @include('_snippets/homepage_separator')

        <h2>
            How does Rector <span class="text-rector-green">Improve your Business</span>?
        </h2>

        <div class="text-medium">
            <p>
                Rector is a PHP tool that you can run on any PHP project to get an instant upgrade
                or automated refactoring.
            </p>

            <p>
                It helps you with:

                <ul>
                    <li>PHP and framework upgrades,</li>
                    <li>in-house framework migrations,</li>
                    <li>improving your code quality to deliver features faster than competition</li>
                </ul>
            </p>

            <p>
                In the hands of an expert, Rector massively reduces your work-time.<br>
                Where project upgrade PHP 8.0 to 8.3 would <strong>take 3 months</strong>, <strong>Rector is done in 3 days</strong>.
            </p>

            <p>
                You can learn it yourself from <a href="{{ action(\App\Controller\DocumentationController::class) }}">documentation</a>,
                or to save time and start upgrading today,
                <strong><a href="{{ action(\App\Controller\HireTeamController::class) }}">hire our upgrade team</a></strong>.
            </p>
        </div>

        @include('_snippets/homepage_separator')

        <div class="text-center mt-5 mb-5 text-rector-green text-bigger">
            We've helped <strong>50+ companies</strong> to improve their PHP code and reduce
            technical debt
        </div>

        @include('_snippets/line/company_logos')

        @include('_snippets/homepage_separator')

        <h2>
            We Deliver <span class="text-rector-green">10x Faster and Cheaper</span><br>
            Thanks to Experience With <span class="text-rector-green">50+ Upgrades</span>
        </h2>

        <div class="row">
            <div class="col-12 col-md-8 text-center">
                <img src="/assets/images/rector_chart.png" alt="Rector Chart"
                     class="img-fluid img-thumbnail">
                <p class="text-smaller text-secondary">
                    * Based on average data from 52 projects.
                </p>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <div class="text-bigger">
                    <p>
                        90% of problems you'll face are&nbsp;new to you.
                    </p>
                    <p>
                        We've already seen them and&nbsp;know exactly how to solve them cost-effectively
                        and quickly.
                    </p>

                    <p>
                        How typical <a href="{{ action(\App\Controller\CodebaseRenovationController::class) }} ">codebase renovation</a> looks like?
                    </p>
                </div>
            </div>
        </div>

        <!--
        @todo they write about us
        * post
        * rector talk at laracon https://www.youtube.com/watch?v=qGrsvcWdERc&ab_channel=LaraconEU
        -->

        @include('_snippets/homepage_separator')

        <h2>
            Rector <span class="text-rector-green">Empowers Your Project</span>
        </h2>

        @include('homepage/_parts/benefits')

        <br>

        <div class="text-center mt-5">
            <a href="{{ action(\App\Controller\CodebaseRenovationController::class) }}"
               class="btn btn-success btn-lg">
                How do we Renovate Codebases?
            </a>
        </div>

        @include('_snippets/homepage_separator')

        <h2>
            What Do Companies <span class="text-rector-green">Love&nbsp;About&nbsp;Rector</span>?
        </h2>

        @include('hire_team/references')

        <br>

        <div class="text-center mt-2 mt-md-5">
            <a href="{{ action(\App\Controller\ContactController::class) }}"
               class="btn btn-success btn-lg ms-3">
                Contact us to Join them
            </a>
        </div>

        @include('_snippets/homepage_separator')

        <h2>FAQ</h2>

        @include('homepage/_parts/faq')

        <div class="text-center mt-5">
            <a href="{{ action(\App\Controller\HireTeamController::class) }}"
               class="btn btn-success btn-lg">
                Do you have more questions? Ask us
            </a>
        </div>
    </div>
@endsection
