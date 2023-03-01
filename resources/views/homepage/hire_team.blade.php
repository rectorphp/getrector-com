@extends('base')

@section('main')
    <div id="hire_team">
        <h1 class="main-title">{{ $page_title }}</h1>

        <p class="mt-5 text-bigger">
            Your project is a success, but why is the development slowing down?
            <br>
            Our clients used to have similar problem.
        </p>

        <p class="text-bigger">
            We help them to <strong>cut feature development costs to a fraction</strong> and ensure their teams are <strong>productive again</strong>.
        </p>

        <br>

        <div class="text-center mt-5">
            <div class="text-rector-green mb-5 text-bigger">
                <strong>12 companies</strong> hired us this year to improve PHP code and reduce technical debt
            </div>

            @include('_snippets/line/company_logos')
        </div>

        <hr class="project-border-line">

        <div id="references">
            <div class="row">
                <div class="col-sm-4 text-right pr-5">
                    <img src="/assets/images/logo/logo_bigger/complex.jpg" class="img-fluid company-logo mb-4" style="max-width: 16em">

                    <div class="clearfix"></div>

                    <p>Germany</p>
                </div>
                <div class="col-sm-8">
                    <h3>
                        <strong>Framework Migration, PHP Upgrade and CI Hardening</strong>
                    </h3>

                    <ul>
                        <li>Set up advanced custom PHPStan rules</li>
                        <li>Increase type coverage, Rector and ECS integration</li>
                        <li>Optimization of Processes</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow border-success mt-4">
                <div class="card-body company-quote-card-body" style="display: flex">
                    <div>
                        <img src="https://avatars.githubusercontent.com/u/47448731?v=4" class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                    </div>

                    <blockquote class="blockquote company-quote me-4 mb-5">
                        <p>
                            "It's a lot of fun and we killed/transformed already a lot of legacy code.
                            Using our very own continuous deployment pipeline <strong>we shipping this changes to production since day 1</strong>."
                        </p>
                        <footer class="blockquote-footer mt-1">
                            Markus Staab,
                            Project Technical Lead
                        </footer>
                    </blockquote>
                </div>
            </div>

            <hr class="project-border-line">

            <div class="row">
                <div class="col-sm-4 text-right pr-5">
                    <img src="/assets/images/logo/logo_bigger/ares_travel.svg" class="img-fluid company-logo mb-4" style="max-width: 16em">

                    <div class="clearfix"></div>

                    <p>California</p>
                </div>
                <div class="col-sm-8">
                    <h3>
                        <strong>Upgrade from PHP 7.2, Symfony 2.8 to fully-typed PHP 8</strong>
                    </h3>

                    <ul>
                        <li>Set up a high quality CI pipeline battery with bulletproof checks</li>
                        <li>Removed dead methods and classes, unused packages, TWIG filters and helpers</li>
                        <li>Highly improved unit test coverage to make changes more reliable and safer</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow border-danger mt-4">
                <div class="card-body company-quote-card-body" style="display: flex">
                    <div>
                        <img src="https://www.adamgleiss.com/images/face.jpg" class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                    </div>

                    <blockquote class="blockquote company-quote me-4">
                        <p>
                            "I'm <strong>extremely pleased with the progress</strong> we are making.<br>
                            It's really come a long way."

                        </p>
                        <footer class="blockquote-footer mt-1">
                            William Adam Gleiss,
                            VP of Technology at aRes Travel
                        </footer>
                    </blockquote>
                </div>
            </div>

            <hr class="project-border-line">

            <div class="row">
                <div class="col-sm-4 text-right pr-5">
                    <img src="/assets/images/logo/logo_bigger/eonx.png" class="img-fluid company-logo" style="max-width: 12em">

                    <div class="clearfix mb-4"></div>

                    <p>Australia</p>
                </div>
                <div class="col-sm-8">
                    <h3>
                        <strong>Upgrade from Laravel 5.5</strong>
                    </h3>

                    <ul>
                        <li>Implementation of ECS, PHPStan and Rector</li>
                        <li>Standardization of namespace</li>
                        <li>Standardization of tests</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow border-primary mt-4">
                <div class="card-body company-quote-card-body" style="display: flex">
                    <div>
                        <img src="https://user-images.githubusercontent.com/77585053/110624235-38a3b200-819e-11eb-9ecc-a8f7b62d3da3.jpeg" class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                    </div>

                    <blockquote class="blockquote company-quote me-4">
                        <p>
                            "From upgrading our legacy projects and <strong>improving team's productivity, to making code review easier and reliable</strong>, Rector is in the center within our PHP ecosystem."
                        </p>
                        <footer class="blockquote-footer mt-1">
                            Nathan Page, Technical Lead at EONX
                        </footer>
                    </blockquote>
                </div>
            </div>

            <hr class="project-border-line">

            <div class="row mt-4">
                <div class="col-sm-4">
                    <a href="https://spaceflow.io/">
                        <img src="/assets/images/logo/logo_bigger/spaceflow.png" class="img-fluid mt-3" style="max-width: 16em">
                    </a>

                    <div class="clearfix mb-4"></div>

                    <p>New York</p>
                </div>
                <div class="col-sm-8">
                    <h3>
                        <strong>Upgrade from Symfony 3.4</strong>
                    </h3>


                    <ul>
                        <li>Migration from Doctrine ID to UUID</li>
                        <li>Implementation of Gitlab CI with ECS, PHPStan and Rector</li>
                        <li>Replacement of messy classes with PSR-4</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mt-4 border-warning">
                <div class="card-body company-quote-card-body" style="display: flex">
                    <div>
                        <img src="https://user-images.githubusercontent.com/77585053/110624233-37728500-819e-11eb-9efb-269e161c2ad4.jpeg" class="rounded-circle img-face-smaller-left shadow-sm me-4" alt="">
                    </div>

                    <blockquote class="blockquote company-quote me-4">
                        <p>
                            "Thanks to Rector, we were able to quite simply refactor the core of our API, which <strong>saved a lot of work</strong> that our developers would otherwise have to do manually."
                        </p>
                        <footer class="blockquote-footer mt-1">
                            Milan Mimra,
                            CTO at Spaceflow
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <hr class="project-border-line">

        <h2>What Relationship Do We Build with You?</h2>

        <p class="text-medium">
            We <strong>build long-term relationships</strong> with our clients. Haphazard changes can quickly backfire and introduce regression bugs. But small, gradual steps lead to <strong>long-term success</strong>.
        </p>

        <p class="text-medium">
            We get you to the latest PHP/framework version, make CI the next member of your team and help you deliver features faster and cheaper.
        </p>

        <hr class="project-border-line">

        <h2>What Does the Process Look Like?</h2>

        <h3 id="intro_analysis">1. Intro Analysis</h3>

        <p>
            At first, we have to make sure we can help you. We explore the weak spots that would complicate the process and get ready for the battle.
        </p>

        <h4>What steps are involved?</h4>

        <ul>
            <li>We sign an NDA so your code is safe with us</li>
            <li>Then you share your codebase with us, e.g. in a git repository</li>
            <li>We look into the code with various tools, exploit weak spots and work out the step-by-step plan tailored to your project</li>
            <li>The analysis <strong>takes 3 weeks</strong></li>
            <li>Then we <strong>send you a PDF with the results and a suggested plan</strong> so you can discuss it with your team</li>
            <li>We finish this phase with a call about how to proceed</li>
        </ul>

        <br>

        <h3 id="hands_on_upgrade">2. Hands-on Upgrade</h3>

        <p>
            After we agree on next steps, we start the upgrade work. Our team provides <strong>10-30 hours/week of direct help</strong>, depending on the pace you prefer. We dedicate a developer with Rector experience on legacy code to your project.
        </p>

        <p>
            This phase usually <strong>takes 6-12 months</strong> depending on the upgrade target and code quality of your project.
        </p>

        <h4>What steps are involved?</h4>

        <ul>
            <li>We improve code quality to make bigger changes more reliable</li>
            <li>We deliver the smallest PR possible so that you can see changes yourself and merge it immediately</li>
            <li>We make sure the work is always in a finished state in every PR</li>
            <li>We ensure CI helps catch regressions so there is a safety net, and we can move without fear</li>
            <li>
                We <strong>work in parallel</strong> to your team, so you can keep delivering features and <strong>grow your business</strong>
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
                        <a href="{{ route(\Rector\Website\Enum\RouteName::CONTACT) }}" class="btn btn-success text">Contact us</a>
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
                        <a href="{{ route(\Rector\Website\Enum\RouteName::CONTACT) }}" class="btn btn-success text">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
