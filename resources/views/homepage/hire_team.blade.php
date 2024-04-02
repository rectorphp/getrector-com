@extends('base')

@section('main')
    <div id="hire_team">
        <h1 class="main-title">{{ $page_title }}</h1>

        <p class="mt-5 text-bigger">
            Your project is a success, but your technical debt is slowing you down?
            <br>
            Our clients used to have similar problem.
        </p>

        <p class="text-bigger">
            We help you to <strong>cut feature development costs to a fraction</strong><br>
            and make your team <strong>productive and happy again</strong>.
        </p>

        <br>

        <div class="text-center mt-5">
            <div class="text-rector-green mb-5 text-bigger">
                We've helped <strong>50+ companies</strong> to speed-up work and reduce technical debt
            </div>
        </div>

        <br>

        @include('hire_team/references')

        <br>

        <h2>Strong and Reliable Relationship with you is our Priority</h2>

        <p>
            We <strong>build long-term relationships</strong> with our clients. Haphazard changes
            can quickly backfire and introduce regression bugs. But small, gradual steps lead to
            <strong>long-term success</strong>.
        </p>

        <p>How do we build such a relationship? Step by step.</p>

        <h2 id="intro_analysis">Phase 1: Intro Analysis</h2>

        <div class="col-12 col-sm-5 float-end">
            <div class="card shadow ms-3">
                <div class="card-header">
                    <h3 class="card-title m-2 text-center">In short</h3>
                </div>
                <div class="card-body">

                    <ul class="list-nobullet">
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            We highlight weak spots in your code
                        </li>
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            We deliver a plan detailing upgrade steps
                        </li>
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            Every phase has a detailed scope and a "why"
                        </li>
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            Good choice for a yearly project health check
                        </li>

                        <br>

                        <li>
                            <em class="fas fa-calendar text-info fa-fw"></em>
                            <strong>Delivered in 3 weeks</strong>
                        </li>

                        <li class="mt-4 mb-3">
                            <em class="fa fa-credit-card text-primary fa-fw"></em>&nbsp;
                            One time charge of 6 000-8 000 €
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <p>
            At first, we have to make sure we are able to help you in your time and budget constraint. We look into your codebase and explore the weak spots that would complicate the process. Then we get ready for the battle.
        </p>

        <h4>What steps are involved?</h4>

        <p>
            2. In the intro call, you tell us about you problems, state of your project and your goals
        </p>
        <p>
            3. <strong>We sign an NDA</strong>, so your project is safe with us (got ready-made NDA to save time)
        </p>
        <p>
            4. <strong>You share a Git repository</strong> with us, so we have an idea about project size
        </p>
        <p>
            5. We give you a quote for <a href="hire-team#intro_analysis">intro analysis</a> of your project, that fits within 6 000-8 000 €
        </p>
        <p>
            6. After we confirm, we send you an invoice
        </p>

        <p>
            7. <strong>Upon the payment, we start an intro analysis</strong>
        </p>

        <ul>
            <li>We sign an NDA so your code is safe with us</li>
            <li>Then you share your codebase with us, e.g. in a git repository</li>
            <li>We look into the code with various tools, exploit weak spots and work out the
                step-by-step plan tailored to your project
            </li>
            <li>The analysis <strong>takes 3 weeks</strong></li>
            <li>Then we <strong>send you a PDF with the results and a suggested plan</strong> so you
                can discuss it with your team
            </li>
            <li>We finish this phase with a call about how to proceed</li>
        </ul>

        <a href="/assets/demo_intro_analysis.pdf" class="btn btn-warning me-3">
            <em class="fa fa-file-pdf fa-fw"></em>
            Open Demo PDF
        </a>

        <br>


        <div class="clearfix"></div>

        <br>
        <br>

        <hr class="project-border-line m-4">

        <div class="text-center">
            After we deliver the intro analysis in PDF, we'll have a call about next steps.
            <br>
            We agree on the best place to start and move to the 2nd phase.
        </div>

        <hr class="project-border-line m-4">

        <br>

        <div class="clearfix"></div>

        <h2 id="hands_on_upgrade">Phase 2: Hands-on Upgrade</h2>

        <div class="col-12 col-sm-5 float-end">
            <div class="card shadow ms-3">
                <div class="card-header">
                    <h3 class="card-title m-2 text-center">
                        In short
                    </h3>
                </div>

                <div class="card-body">
                    <ul class="list-nobullet">
                        <li class="mb-4">
                            <em class="fa fa-star text-warning fa-fw"></em>&nbsp;
                            <strong>We handle full project upgrade</strong>
                        </li>
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            We improve your CI with advanced tooling
                        </li>
                        <li>
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            We share our experience of 50+ project upgrades
                        </li>
                        <li class="mb-4">
                            <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                            You learn smart tricks with tools you already use
                        </li>

                        <li class="mb-4">
                            <em class="fas fa-calendar text-info fa-fw"></em>
                            &nbsp;Ongoing 6-12 months
                        </li>

                        <li class="mt-4 mb-3">
                            <em class="fa fa-credit-card text-primary fa-fw"></em>&nbsp;
                            Charged hourly, from 20 hours/week<br>and 140-160 €/hour
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <p>
            In this phase, we provide <strong>20-40 hours/week of direct help</strong>, depending on the pace you prefer. We dedicate a developer with Rector experience on legacy code to your project.
        </p>

        <h4>What steps are involved?</h4>

        <ul>
            <li>We improve code quality to make bigger changes more reliable</li>
            <li>We deliver the smallest PR possible so that you can see changes yourself and merge
                it immediately
            </li>
            <li>We make sure the work is always in a finished state in every PR</li>
            <li>We ensure CI helps catch regressions so there is a safety net, and we can move
                without fear
            </li>
            <li>
                We <strong>work in parallel</strong> to your team, so you can keep delivering
                features and <strong>grow your business</strong>
            </li>
        </ul>
    </div>

    <div class="clearfix"></div>

    <br>
    <br>
    <br>

@endsection
