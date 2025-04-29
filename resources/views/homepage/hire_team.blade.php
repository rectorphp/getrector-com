@extends('base')

@section('main')
    <div id="hire_team">
        <h1 class="main-title">{!! $page_title !!}</h1>

        <div class="offset-1 offset-md-2 col-10 col-md-12 text-bigger">
            <p>
                Your project is a success, but your technical debt is slowing you down?
                <br>
                Our clients used to have similar problem.
            </p>

            <p class="mb-5">
                We help you to <strong>cut feature development costs to a fraction</strong><br>
                and make your team <strong>productive and happy again</strong>.
            </p>

            <p>
                We build a long-term and <strong>reliable relationship with you</strong> &ndash; our
                client.
            </p>

            <p>
                Haphazard changes can quickly backfire and introduce regression bugs.
                <br>
                That's why we take small, safe, gradual steps to reach our goal.

                <br><br>
                Our cooperation has 2 phases.
            </p>
        </div>

        <hr class="project-border-line m-5">

        <div class="col-12 col-sm-5 float-end pe-3 pe-md-0 mb-5 mb-md-2 ps-md-4">
            <img src="/assets/images/intro-analysis.jpg" class="rounded-3 mb-3" style="max-width: 100%">

            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title m-2 text-center">In short</h3>
                </div>
                <div class="card-body">

                    <ul class="list-nobullet">
                        <li>
                            @include("icons/success_check")
                            We sign NDA, so you're safe with repository share
                        </li>
                        <li>
                            @include("icons/success_check")
                            We deep dive into your codebase
                        </li>

                        <li>
                            @include("icons/success_check")
                            We run automated tools to find spots we'll have to address during the
                            upgrade
                        </li>

                        <li>
                            @include("icons/success_check")
                            We highlight specific weak spots
                        </li>

                        <li>
                            @include("icons/success_check")
                            We deliver a plan detailing upgrade steps
                        </li>

                        <li class="mb-4">
                            @include("icons/success_check")
                            Every phase has a detailed scope and explain its role in the process
                        </li>

                        <li class="mb-4">
                            @include('icons/calendar')
                            <strong>Delivered in 3 weeks</strong>
                        </li>

                        <li class="mb-3">
                            @include("icons/credit_card")
                            One time charge of 6 000-8 000 €
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <h2 id="intro_analysis">Phase 1: Intro Analysis</h2>

        <p>
            In the first meeting, you'll tell us about you problems, state of your project and your
            goals.
        </p>

        <p>
            We want to make sure we are able to help you within your time and budget constraints. We
            do that by doing and "intro analysis" of your project. That means we look into your
            codebase, where we explore the weak spots that would complicate the process.
        </p>

        <p>
            <strong>We sign an NDA</strong>, so your project is safe with us.
        </p>

        <p>
            After you <strong>share a Git repository</strong> with us, we measure project size and
            comlexity, and give you intro analysis quota &ndash; a one-time charge that ranges 6&nbsp;000-8&nbsp;000
            €.
        </p>

        <p>
            After we confirm the price, we send you an invoice. Upon the payment <strong>we start to
                work on the intro analysis</strong>.

        </p>

        <p>
            Result of this phase is <strong>a PDF report with timeline and step-by-step process
                tailored to your project</strong>. To give you an idea about the contents of intro
            analysis, we prepared a <a href="/assets/demo_intro_analysis.pdf">demo intro
                analysis</a>.
        </p>

        <br>

        <div class="clearfix"></div>


        <hr class="project-border-line m-5">

        <div class="text-left offset-1">
            <p>
                After we deliver the intro analysis in PDF, you can explore the plan with your team.
                <br>
                Then we'll have a call about next steps.
                <br>
                We agree on the best place to start and move to the 2nd phase.
            </p>
        </div>

        <hr class="project-border-line m-5">

        <div class="clearfix"></div>

        <div class="col-12 col-sm-5 float-end ps-0 pe-3 pe-md-0 mb-5 mb-md-2 ps-md-4">
            <img src="/assets/images/hands-on-upgrade.jpg" class="rounded-3 mb-3" style="max-width: 100%">

            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title m-2 text-center">In short</h3>
                </div>

                <div class="card-body">
                    <ul class="list-nobullet">
                        <li class="mb-4">
                            @include('icons/star')
                            <strong>We handle full project upgrade</strong>
                        </li>
                        <li>
                            @include("icons/success_check")
                            We improve your CI with advanced tooling
                        </li>
                        <li>
                            @include("icons/success_check")
                            We share our experience of 50+ project upgrades
                        </li>
                        <li>
                            @include("icons/success_check")
                            We handle upgrade and code-quality back and forth, as both are need to
                            healthy project
                        </li>
                        <li class="mb-4">
                            @include("icons/success_check")
                            You learn smart tricks with tools you already use
                        </li>

                        <li class="mb-4">
                            @include('icons/calendar')
                            &nbsp;Typically 6-12 months
                        </li>

                        <li class="mt-4 mb-3">
                            @include("icons/credit_card")
                            Charged hourly, from 20-60 hours/week<br>and 140-160 €/hour
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <h2 id="hands_on_upgrade">Phase 2: Hands-on Upgrade</h2>

        <p>
            In this phase, we provide <strong>20-80 hours/week of direct help</strong>, depending on
            the pace you prefer. We dedicate a developer with Rector experience on legacy code to
            your project.
        </p>

        <p>We'll work on improving code quality to make bigger changes more reliable and stable.
            <strong>We start with low-hanging fruit so your team feels comfortable with us.</strong>
        </p>

        <p>
            We always deliver as small changes as possible, so you can understand changes yourself
            and merge it immediately. We make sure <strong>the work is always in a finished
                state</strong>, no weeks-ongoing in-the-air changes.</p>

        <p>
            We <strong>work in parallel</strong> to your team, so you can keep delivering features
            and <strong>grow your business</strong>.
        </p>

        <p>
            We can schedule <strong>once-a-month meeting</strong> to discuss progress, blockers and
            address just in time direction of the upgrade.
        </p>
    </div>



    <div class="clearfix" style="clear: both"></div>

    <br>
    <br>
    <br>

    <div class="text-center mt-2">
        <a href="{{ action(\App\Controller\ContactController::class) }}"
           class="btn btn-success btn-lg ms-3">
            Do you want to start today? Contact us
        </a>
    </div>

    <br>
    <br>
    <br>

@endsection
