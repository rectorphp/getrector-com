@extends('base')

@php
    /** @var $post \Rector\Website\Entity\Post */
    $page_title = $post->getTitle();
@endphp


@section('social_tags')
    <meta property="og:title" content="{{ $post->getClearTitle() }}"/>
    <meta property="og:description" content="{{ $post->getPerex() }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="{{ route(\Rector\Website\Enum\RouteName::POST_IMAGE, ['title' => $post->getClearTitle()]) }}"/>

    <meta
        property="og:url"
        content="{{ route(\Rector\Website\Enum\RouteName::POST, ['postSlug' => $post->getSlug()]) }}"
    />

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $post->getClearTitle() }}"/>
    <meta name="twitter:image" content="{{ route(\Rector\Website\Enum\RouteName::POST_IMAGE, ['title' => $post->getClearTitle()]) }}"/>
    <meta name="twitter:description" content="{{ $post->getPerex() }}"/>
@endsection

    @section('main')
    <div class="alert alert-info mt-3 mb-5" role="alert">
        Would you like to <strong>learn Rector in depth</strong>?
        The <strong>Rector - The Power of Automated Refactoring</strong> book is out now.
        <a href="https://leanpub.com/rector-the-power-of-automated-refactoring?utm_source=getrector_post_detail">Grab a copy!</a>
    </div>

    <div id="post">
        <div class="mt-3">
            <time datetime="{{ $post->getDateTime()->format('Y-m-D') }}" class="text-grey">
                {{ $post->getDateTime()->format('Y-m-D') }}
            </time>
        </div>

        <h1>{{ $post->getTitle() }}</h1>

        @if ($post->getSinceRector())
            <div class="alert alert-warning">
                This feature is available since <strong>Rector {{ $post->getSinceRector() }}</strong>.
            </div>
        @endif

        @if ($post->isUpdated())
            <div class="card border-success card-bigger mt-5">
                <div class="card-header text-white bg-success">
                    <strong>{{ $post->getUpdatedAt()->format("F Y") }} Update</strong>
                </div>
                @if ($post->getUpdatedMessage())
                    <div class="card-body pb-2">
                        <x-markdown>
                            {{ $post->getUpdatedMessage() }}
                        </x-markdown>
                    </div>
                @endif
            </div>

            <br>
        @endif

        <div class="perex">
            <x-markdown>
                {{ $post->getPerex() }}
            </x-markdown>
        </div>

        <div class="text-body">
            {!! $post->getHtmlContent() !!}
        </div>

        <br>
        <br>
        <br>

        <div class="container">
            @include('_snippets/disqus_comments')
        </div>

        <br>
    </div>
@endsection
