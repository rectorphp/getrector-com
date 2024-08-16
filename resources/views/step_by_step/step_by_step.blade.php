@extends('base')

@section('main')
    <div id="simple_page">
        <h1 class="mb-5">Step by Step</h1>

        <div class="text-medium">
            <p>
                Do you have a project you want to upgrade? It takes roughly 200-300 steps to get the most out of Rector... but where to start to avoid blind paths? This tool will guide you through the process, step by step with <code>rector.php</code> contents.
            </p>
        </div>

        <br>

        @livewire('step-by-step-component')
    </div>
@endsection
