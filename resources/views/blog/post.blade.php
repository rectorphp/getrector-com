@php
    /** @var $post \Rector\Website\Entity\Post */
@endphp

@extends('base')

@section('social_tags')
    <meta property="og:title" content="{{ $post->getClearTitle() }}"/>
    <meta property="og:description" content="{{ $post->getPerex() }}"/>
    <meta property="og:type" content="article"/>
    <meta
            property="og:image"
            content="{{ action(\Rector\Website\Http\Controllers\ThumbnailController::class, ['title' => $post->getClearTitle()]) }}"
    />

    <meta
            property="og:url"
            content="{{ action(\Rector\Website\Http\Controllers\PostController::class, ['postSlug' => $post->getSlug()]) }}"
    />

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $post->getClearTitle() }}"/>
    <meta
            name="twitter:image"
            content="{{ action(\Rector\Website\Http\Controllers\ThumbnailController::class, ['title' => $post->getClearTitle()]) }}"
    />
    <meta name="twitter:description" content="{{ $post->getPerex() }}"/>

    @if ($post->hasTweets())
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    @endif
@endsection

@section('main')
    <div class="alert alert-info mt-3 mb-5" role="alert">
        Do you want to <strong>learn Rector in depth and fast</strong>?

        The <strong>Rector book 2024 edition</strong> is out now.

        <a href="{{ action(\Rector\Website\Http\Controllers\BookController::class) }} ">Grab your copy!</a>
    </div>

    <div id="post">
        <div class="mt-3">
            <time datetime="{{ $post->getDateTime()->format('Y-m-d') }}" class="text-secondary">
                {{ $post->getDateTime()->format('Y-m-d') }}
            </time>
        </div>

        <h1>{{ $post->getTitle() }}</h1>

        @if ($post->getSinceRector())
            <div class="alert alert-warning">
                This feature is available since
                <strong>Rector {{ $post->getSinceRector() }}</strong>.
            </div>
        @endif

        @if ($post->getUpdatedAt() instanceof DateTimeInterface)
            <div class="card border-success card-bigger mt-5">
                <div class="card-header text-white bg-success">
                    <strong>{{ $post->getUpdatedAt()->format("F Y") }} Update</strong>
                </div>
                @if ($post->getUpdatedMessage())
                    <div class="card-body pb-2">
                        {!! markdown($post->getUpdatedMessage()) !!}
                    </div>
                @endif
            </div>

            <br>
        @endif

        <div class="perex">
            {!! markdown($post->getPerex()) !!}
        </div>

        <div class="text-body">
            {!! markdown($post->getContents()) !!}
        </div>

        <br>
        <br>
    </div>
@endsection
