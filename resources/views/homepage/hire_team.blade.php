@extends('base')

@section('main')
    <style>
        .in-short-card:hover {
            transform: translateY(-3px);
            transition: transform 0.2s ease-in-out;
        }
        .in-short-card svg {
            margin-right: 0.5rem;
            flex-shrink: 0;
        }
    </style>

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
                Haphazard changes can quickly backfire and introduce regression bugs.
                <br>
                That's why we take small, safe, gradual steps to reach our goal.
            <p>

            </p>
            Our cooperation has 2 phases.
            </p>
        </div>

        <hr class="project-border-line m-5">

        <h2 id="intro_analysis">Phase 1: Intro Analysis</h2>

        <div class="col-12 col-sm-5 float-end pe-3 pe-md-0 mb-5 mb-md-2 ps-md-4">
            <img src="/assets/images/intro-analysis.jpg" class="rounded-3 mb-3" style="max-width: 100%">
        </div>

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
            comlexity, and give you intro analysis quota – a one-time charge that ranges 6 000-8 000
            $.
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

        <h3 class="card-title mb-3 fw-bold">In Short</h3>

        <div class="row" style="font-size: 1.4em !important;">
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    <span class="fs-6">We sign NDA for safe repository sharing</span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    <span class="fs-6">We deep dive into your codebase</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    <span class="fs-6">Automated tools identify upgrade spots</span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    <span class="fs-6">We highlight specific weak spots</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    <span class="fs-6">Detailed upgrade plan delivered</span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    <span class="fs-6">Each phase scoped with clear roles</span>
                </div>
            </div>
            <div class="col-6">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include("icons/success_check")
                    <span class="fs-6"><strong>Delivered in 3 weeks</strong></span>
                </div>
            </div>
            <div class="col-6">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include("icons/credit_card")
                    <span class="fs-6">One-time charge of 6,000-8,000 $</span>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <br>

        <hr class="project-border-line m-5">

        <div class="text-left">
            <p>
                After we deliver the intro analysis in PDF, you can explore the plan with your team.
                <br>
                Then we'll have a call about next steps.
                <br>
                We agree on the best place to start and move to the 2nd phase.
            </p>
        </div>

        <hr class="project-border-line m-5">

        <br>

        <div class="clearfix"></div>



        <h2 id="hands_on_upgrade">Phase 2: Hands-on Upgrade</h2>

        <div class="col-12 col-sm-5 float-end ps-0 pe-3 pe-md-0 mb-5 mb-md-2 ps-md-4">
            <img src="/assets/images/hands-on-upgrade.jpg" class="rounded-3 mb-3" style="max-width: 100%">
        </div>

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

        <h3 class="card-title mb-3 fw-bold">In Short</h3>

        <div class="row" style="font-size: 1.4em">
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    @include('icons/star')
                    <span class="fs-6"><strong>Full project upgrade handled</strong></span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include("icons/success_check")
                    <span class="fs-6">Advanced CI tooling improvements</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    @include("icons/success_check")
                    <span class="fs-6">50+ upgrades’ experience shared</span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include("icons/success_check")
                    <span class="fs-6">Balanced upgrade and code quality</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm">
                    @include("icons/success_check")
                    <span class="fs-6">Learn tricks with your tools</span>
                </div>
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include('icons/calendar')
                    <span class="fs-6">Typically 6-12 months</span>
                </div>
            </div>
            <div class="col-12">
                <div class="in-short-card p-3 bg-white rounded-3 shadow-sm mt-3">
                    @include("icons/credit_card")
                    <span class="fs-6">Hourly billing, 20-60 hours/week at 140-160 $/hour</span>
                </div>
            </div>
        </div>
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
