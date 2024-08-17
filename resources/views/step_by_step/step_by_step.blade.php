@extends('base')

@section('main')
    <div id="simple_page">
        <h1 class="mb-5">Step by Step</h1>

        <div class="text-medium">
            <p>
                Do you have a project you want to upgrade? It takes around 250-300 steps to get the most out of Rector <strong>in the right order</strong>. But where to start to avoid blind paths? This tiny tool will guide you, showing <code>rector.php</code> contents.
            </p>
        </div>

        <br>
        <br>

        @livewire('step-by-step-component')
    </div>
@endsection
