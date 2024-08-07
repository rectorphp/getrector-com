@extends('base')

@php
    /** @var \App\ValueObject\RenovationItem[] $renovationItems */
@endphp

@section('main')
    <div id="simple_page">
        <h1 class="main-title">{{ $page_title }}</h1>

        <div class="text-bigger text-center">
            <p class="text-bigger">
                We elevate your project to its peak potential with software upgrade service.
            <p>

            <p>
                While every project is unique, we offer you <strong>concrete examples of transformation</strong>.<br>
                That way you can envision the <strong>improvements your project will achieve</strong> once Rector handles it.
            </p>
        </div>

        <br>

        @foreach ($renovationItems as $renovationItem)
            <h2 class="text-center mt-5 mb-5" >{{ $loop->iteration }}. {{ $renovationItem->getTitle() }}</h2>

            <div class="row">
                <div class="col-6">
                    <h3 style="opacity: .7">Before</h3>

                    <p>{!! $renovationItem->getDescriptionBefore() !!}</p>
                    <pre>
                        <code class="dust language-{{ $renovationItem->getSnippetLanguage() }}">{{ $renovationItem->getCodeSnippetBefore() }}</code>
                    </pre>
                </div>
                <div class="col-6">
                    <h3>After</h3>

                    <p>{!! $renovationItem->getDescriptionAfter() !!}</p>
                    <pre>
                        <code class="language-{{ $renovationItem->getSnippetLanguage() }}">{{ $renovationItem->getCodeSnippetAfter() }}</code>
                    </pre>
                </div>
            </div>

            <br>

{{--            @include('_snippets/homepage_separator')--}}
        @endforeach

    </div>

    <style>
        .dust {
            opacity: .75;
            filter: grayscale(100%);
        }
    </style>
@endsection
