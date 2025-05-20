@php
    /** @var \App\Entity\Post $recentPosts */
@endphp

@extends('base')

@section('main')
    <div class="container mobile-margin-top">
        <div id="homepage">
            <h1 class="main-title">
                The Way You Can Finally
                <br>
                <span class="text-rector-green">Upgrade PHP</span>
            </h1>

            <div class="text-bigger text-center">
                <p>We help successful and growing companies to get the most out of the code they
                    already have.</p>

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
        </div>
    </div>

    <div style="background: #FFF" class="mb-5">
        <div class="container" style="max-width: 78em;">
            <h3 class="mt-5 mb-5 text-center">Trusted by engineers at</h3>

            <div id="company_logos" class="mt-3 mb-5 text-center">
                <img src="assets/images/logo/logo_bigger/curve.svg" alt="Curve logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/i6.png" alt="i6 logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/gotphoto.png" alt="GotPhoto logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/ares_travel.svg" alt="Ares Travel logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/media_trust.png" alt="Media Trust logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/spaceflow.png" alt="Spaceflow logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2">
                <img src="assets/images/logo/logo_bigger/eonx.png" alt="EONX logo"
                     class="img-fluid pt-4 pb-4 pb-sm-2 pt-sm-2 p-3">
            </div>
        </div>
    </div>

    <div class="container mobile-margin-top">
        <div id="homepage">
            <h2>
                How does Rector <span
                    class="text-rector-green">Improve&nbsp;your&nbsp;Business</span>?
            </h2>

            <div class="text-medium">
                <p>
                    Rector is a PHP tool that you can run on any PHP project to get an instant
                    upgrade
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
                    Where project upgrade PHP 8.0 to 8.4 would <strong>take 3 months</strong>,
                    <strong>Rector is done in 3 days</strong>.
                </p>

                <p>
                    You can learn it yourself from <a
                        href="{{ action(\App\Controller\DocumentationController::class) }}">documentation</a>,
                    or to save time and start upgrading today,
                    <strong><a href="{{ action(\App\Controller\HireTeamController::class) }}">hire
                            our upgrade team</a></strong>.
                </p>
            </div>

            <br>
            <hr class="project-border-line m-5">
            <br>


            <h2>
                <span class="text-rector-green">10x Faster and Cheaper</span> upgrades<br>
                with <span class="text-rector-green">50+&nbsp;projects&nbsp;Experience</span>
            </h2>

            @include('homepage/_parts/graphs')

            @include('_snippets/homepage_separator')

            @if (count($upcomingTalks) > 0)
                @include('homepage/_parts/upcoming_talks')
                @include('_snippets/homepage_separator')
            @endif

            @include('homepage/_parts/posts')
            @include('_snippets/homepage_separator')

            <h2>
                What do CTOs <span class="text-rector-green">Love&nbsp;about&nbsp;Rector</span>?
            </h2>

            @include('hire_team/references')
            @include('_snippets/homepage_separator')

            <h2>FAQ</h2>

            @include('homepage/_parts/faq')
        </div>
    </div>
@endsection
