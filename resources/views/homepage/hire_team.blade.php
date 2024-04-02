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

        <div id="references">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="float-end col-3 text-end">
                        <a href="https://www.arestravel.com/">
                            <img src="/assets/images/logo/logo_bigger/ares_travel.svg" class="img-fluid company-logo mb-1" style="max-width: 16em">
                        </a>

                        <p>
                            <em class="fas fa-location-pin"></em>
                            &nbsp;
                            California
                        </p>
                    </div>

                    <h3>
                        <strong>From PHP 7.2 to 8.3, from Symfony 2.8 to 7.0</strong>
                    </h3>

                    <ul class="mt-3">
                        <li>Set up a high-quality CI pipeline with bulletproof checks</li>
                        <li>Removed dead methods and classes, unused packages - over 20 % of code-base</li>
                        <li>Highly improved test coverage to make changes more reliable and safer</li>
                        <li>Covered code with type declarations from 5 % to 99 %</li>
                    </ul>
                </div>

                <div class="card shadow border-danger ms-2 mt-4">
                    <div class="card-body company-quote-card-body" style="display: flex">
                        <div>
                            <img src="https://www.adamgleiss.com/images/face.jpg"
                                 class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                        </div>

                        <blockquote class="blockquote company-quote me-4">
                            <p>
                                "I'm <strong>extremely pleased with the progress</strong> we are making.<br>
                                It's really come a long way."
                            </p>
                            <footer class="blockquote-footer mt-1">
                                William Adam Gleiss, VP of Technology at aRes Travel
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <br>
            <br>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="float-end col-3 text-end">
                        <a href="https://eonx.com/">
                            <img src="/assets/images/logo/logo_bigger/eonx.png" class="img-fluid company-logo mb-3" style="max-width: 8em">
                        </a>

                        <p>
                            <em class="fas fa-location-pin"></em>
                            &nbsp;
                            Australia
                        </p>
                    </div>

                    <h3>
                        <strong>From Laravel 5.5 to 6.0, Setup CI Code Analysis</strong>
                    </h3>

                    <ul>
                        <li>Implemented of ECS with PSR-2, PHPStan from level 0 to 4, and Rector</li>
                        <li>Set up full PSR-4 composer autoloader with unique classes</li>
                        <li>Standardized tests to PSR-4</li>
                    </ul>
                </div>

                <div class="card shadow border-primary  ms-2 mt-4">
                    <div class="card-body company-quote-card-body" style="display: flex">
                        <div>
                            <img src="https://user-images.githubusercontent.com/77585053/110624235-38a3b200-819e-11eb-9ecc-a8f7b62d3da3.jpeg"
                                 class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                        </div>

                        <blockquote class="blockquote company-quote me-4">
                            <p>
                                "From upgrading our legacy project and <strong>improving team's
                                    productivity, to faster and easier code reviews</strong>,
                                Rector is in the center of our PHP ecosystem."
                            </p>
                            <footer class="blockquote-footer mt-1">
                                Nathan Page, Technical Lead at EONX
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <h2>Strong and Reliable Relationship with you is our Priority</h2>

        <p class="text-medium">
            We <strong>build long-term relationships</strong> with our clients. Haphazard changes
            can quickly backfire and introduce regression bugs. But small, gradual steps lead to
            <strong>long-term success</strong>.
        </p>

        <h3 id="intro_analysis">Step 1: We look into your project</h3>

        <p>
            At first, we have to make sure we can help you. We explore the weak spots that would
            complicate the process and get ready for the battle.
        </p>

        <h4>What steps are involved?</h4>

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

        <br>

        <h3 id="hands_on_upgrade">Step 2: Hands-on Upgrade</h3>

        <p>
            After we agree on next steps, we start the upgrade work. Our team provides <strong>10-30
                hours/week of direct help</strong>, depending on the pace you prefer. We dedicate a
            developer with Rector experience on legacy code to your project.
        </p>

        <p>
            This phase usually <strong>takes 6-12 months</strong> depending on the upgrade target
            and code quality of your project.
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

        <hr class="project-border-line">

        <h2>Choose a Plan That Fits Your Project</h2>

        <div class="row mt-5">
            <div class="col-12 col-sm-6">
                <div class="card shadow m-3">
                    <div class="card-header text-center bg-dark border-dark bg-opacity-10 border-opacity-25 pt-3 pb-3">
                        Starter Choice
                    </div>
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">Intro Analysis</h3>

                        <ul class="list-nobullet">
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                We highlight current weak spots in your code
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
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                Delivered in 3 weeks
                            </li>

                            <li class="mt-4 mb-3">
                                <em class="fa fa-credit-card text-primary fa-fw"></em>&nbsp;
                                One time charge
                            </li>

                            <li>
                                <em class="fa fa-star text-warning fa-fw"></em>&nbsp;
                                <strong>You apply the plan yourself</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer text-center pt-4 pb-4">
                        <a href="/assets/demo_intro_analysis.pdf" class="btn btn-warning me-3">
                            <em class="fa fa-file-pdf fa-fw"></em>
                            Open Demo PDF
                        </a>
                        <a href="{{ action(\Rector\Website\Http\Controller\ContactController::class) }}"
                           class="btn btn-success text">Contact us</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="card shadow m-3">
                    <div class="card-header text-center bg-success text-white bg-opacity-75 border-success pt-3 pb-3 border-opacity-50">
                        <strong>Most Wanted Choice</strong>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">
                            Intro Analysis + Hands-on Upgrade
                        </h3>

                        <ul class="list-nobullet">
                            <li class="text-secondary">
                                <em class="fa fa-left-long text-secondary fa-fw"></em>&nbsp;
                                All from previous, and also...
                            </li>
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                We improve your CI with advanced tooling
                            </li>
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                We share our experience of 50+ project upgrades
                            </li>
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                You learn smart tricks with tools you already use
                            </li>
                            <li>
                                <em class="fa fa-check-circle text-success fa-fw"></em>&nbsp;
                                You learn how to avoid making legacy code
                            </li>

                            <li class="mt-4 mb-3">
                                <em class="fa fa-credit-card text-primary fa-fw"></em>&nbsp;
                                Charged per hour, 10-30 hours/week
                            </li>

                            <li>
                                <em class="fa fa-star text-warning fa-fw"></em>&nbsp;
                                <strong>You leave the project upgrade to us</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer text-center pt-4 pb-4">
                        <a href="{{ action(\Rector\Website\Http\Controller\ContactController::class) }}"
                           class="btn btn-success text">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
