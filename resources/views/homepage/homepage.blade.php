@extends('base')

@section('main')
    <div id="homepage">
        <h1 class="main-title">
            The Way You Can Finally
            <br>
            <span class="text-rector-green">Upgrade PHP</span>
        </h1>

        <div class="text-bigger mt-3 text-center">
            <p>We help successful and growing companies to get the most of the code they already
                have.</p>

            <p>
                <strong>Reduce maintenance cost</strong>, <strong>make feature delivery
                    cheaper</strong><br>
                and turn legacy code into sustainable code.
            </p>
        </div>

        <div class="text-center mt-5 mb-5 bigger-buttons">
            <a href="{{ action(\Rector\Website\Http\Controllers\ForCompaniesController::class) }}"
               class="btn btn-lg btn-primary me-3">Hire Upgrade Team</a>
            <a href="{{ action(\Rector\Website\Http\Controllers\Demo\DemoController::class) }}"
               class="btn btn-lg btn-success">Try It Online</a>
        </div>

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
                    * Based on average data from 37 projects.
                </p>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <div class="text-bigger">
                    <p>
                        90% of problems you'll face are new to you.
                    </p>
                    <p>
                        We've already seen them and know exactly how to solve them quickly,
                        efficiently and effectively.
                    </p>
                </div>
            </div>
        </div>

        @include('_snippets/homepage_separator')

        <h2>
            Rector <span class="text-rector-green">Empowers Your Project</span>
        </h2>

        @include('homepage/_parts/benefits')

        @include('_snippets/homepage_separator')

        <div class="text-center mt-5 mb-5 text-rector-green text-bigger">
            <strong>15 companies</strong> hired us this year to improve their PHP code and reduce
            technical debt
        </div>

        @include('_snippets/line/company_logos')

        @include('_snippets/homepage_separator')

        <h2>
            What Do Companies <span class="text-rector-green">Love&nbsp;About&nbsp;Rector</span>?
        </h2>

        @include('homepage/_parts/quotes')

        <br>

        <div class="text-center mt-2 mt-md-5">
            <a href="{{ action(\Rector\Website\Http\Controllers\ForCompaniesController::class) }}"
               class="btn btn-primary btn-lg">
                Hire Rector Team
            </a>

            <a href="{{ action(\Rector\Website\Http\Controllers\ContactController::class) }}"
               class="btn btn-success btn-lg ms-3">
                Contact us to Join them
            </a>
        </div>

        @include('_snippets/homepage_separator')

        <h2>FAQ</h2>

        @include('homepage/_parts/faq')
    </div>
@endsection
