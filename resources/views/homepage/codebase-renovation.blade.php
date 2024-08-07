@extends('base')

@php
    /** @var \App\ValueObject\RenovationItem[] $renovationItems */
@endphp

@section('main')
    <div id="simple_page">
        <h1 class="main-title">{{ $page_title }}</h1>

        @foreach ($renovationItems as $renovationItem)
            <h2>{{ $loop->iteration }}. {{ $renovationItem->getTitle() }}</h2>

            <div class="row">
                <div class="col-6">
                    <h3>Before</h3>

                    <p>{!! $renovationItem->getDescriptionBefore() !!}</p>
                    <pre>
                        <code class="language-{{ $renovationItem->getSnippetLanguage() }}">{{ $renovationItem->getCodeSnippetBefore() }}</code>
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

            @include('_snippets/homepage_separator')
        @endforeach

    </div>
@endsection
