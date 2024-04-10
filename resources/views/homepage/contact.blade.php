@extends('base')

@section('main')
    <div id="contact">
        <h1 class="main-title">{{ $page_title }}</h1>

        <div class="row">
            <div class="col-12 text-bigger">
                <img src="/assets/images/contact.jpg" class="float-end rounded-4 ms-5" style="max-width: 13em">

                <p class="mb-5">Tell us your biggest problems that you struggle with.<br>
                    We won't judge you, we've seen a lot of legacy projects and are here to help.</p>

                <p>Write Tomas at <a href="mailto:tomas@getrector.com">tomas@getrector.com</a>.</p>
            </div>
        </div>

        <hr class="project-border-line">

        <h2>Meet the Rector Team</h2>

        <div class="row">
            <div class="col-12 col-sm-6">
                <h3>Tomas Votruba</h3>
                <p>CEO, Project Onboarding</p>

                <div class="mt-5">
                    <img src="https://user-images.githubusercontent.com/924196/112137773-f5e7ce00-8bd0-11eb-980d-f6f1a4fb3f0d.jpg" class="rounded-circle img-face shadow ms-4">
                </div>

                <div class="text-bigger">
                    <a href="tel:+420776778332">+420 <strong>776 778 332</strong></a>

                    <br>
                    <a href="mailto:tomas@getrector.com">tomas@getrector.com</a>
                </div>

                <div class="mt-3">
                    <a href="https://github.com/tomasvotruba">
                        <em class="fab fa-github fa-fw"></em>
                        TomasVotruba
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <h3>Abdul Malik Ikhsan</h3>
                <p>CTO, Manager of Project Upgrades</p>

                <div class="mt-5">
                    <img src="/assets/images/faces/abdul.jpg" class="rounded-circle img-face shadow ms-4">
                </div>

                <div class="text-bigger">
                    <a href="mailto:samsonasik@getrector.com">samsonasik@getrector.com</a>
                </div>

                <br>

                <div>
                    <a href="https://github.com/samsonasik">
                        <em class="fab fa-github fa-fw"></em>
                        Samsonasik
                    </a>

                </div>
            </div>

            <div class="col-12 col-sm-4 clearfix">
            </div>
        </div>

        <hr class="project-border-line">

        <h2>Company Details</h2>

        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Edukai, s.r.o.</h3>

                <p>
                    Dubova 4<br>
                    460 01, Liberec
                    Czech Republic
                </p>

                <p>
                    ID: 07536721<br>
                    VAT ID: CZ07536721
                </p>
            </div>
            <div class="col-12 col-md-6">
                <h3>Bank Details</h3>

                <p>Account: 2201520677/2010</p>
                <p>
                    IBAN: CZ51 2010 0000 0022 0152 0677
                    <br>
                    BIC/SWIFT: FIOBCZPPXXX
                </p>
            </div>
        </div>
    </div>
@endsection
