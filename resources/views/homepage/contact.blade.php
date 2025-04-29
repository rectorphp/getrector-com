@extends('base')

@section('main')
    <div id="contact">
        <h1 class="main-title">{{ $page_title }}</h1>

        <div class="row">
            <div class="col-12 text-bigger mb-0 pb-0">
                <img src="/assets/images/meeting.jpg" class="float-end rounded-3 border ms-5" style="max-width: 18em" alt="Meeting">

                <p class="mb-5">Tell us your biggest problems that you struggle with.<br>
                    We won't judge you, we've seen a lot of legacy projects and&nbsp;are&nbsp;here to help.</p>

                <p>Write Tomas at <a href="mailto:tomas@getrector.com">tomas@getrector.com</a></p>
            </div>
        </div>

        <hr class="project-border-line">

        <h2>Meet the Rector Team</h2>

        <div class="row">
            <div class="col-12 col-sm-6">
                <h3>Tomas Votruba</h3>
                <p>CEO, Project Onboarding</p>

                <div class="mt-5">
                    <img src="https://user-images.githubusercontent.com/924196/112137773-f5e7ce00-8bd0-11eb-980d-f6f1a4fb3f0d.jpg" class="rounded-circle img-face shadow ms-4" alt="Tomas Votruba face">
                </div>

                <div class="text-bigger">
                    <a href="tel:+420776778332">+420 <strong>776 778 332</strong></a>

                    <br>
                    <a href="mailto:tomas@getrector.com">tomas@getrector.com</a>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <h3>Abdul Malik Ikhsan</h3>
                <p>CTO, Manager of Project Upgrades</p>

                <div class="mt-5">
                    <img src="/assets/images/faces/abdul.jpg" class="rounded-circle img-face shadow ms-4" alt="Abdul Malik Ikhsan face">
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
                    Jagellonska 2427/19<br>
                    Prague, 130 00<br>
                    Czech Republic
                </p>

                <p>
                    ID: 07536721<br>
                    VAT ID: CZ07536721
                </p>
            </div>
        </div>
    </div>
@endsection
