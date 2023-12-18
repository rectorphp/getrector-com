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
@endsection

@section('main')
    <div class="alert alert-info mt-3 mb-5" role="alert">
        Would you like to <strong>learn Rector in depth</strong>?

        The <strong>Rector - The Power of Automated Refactoring</strong> book is out now.

        <a href="{{ action(\Rector\Website\Http\Controllers\BookController::class) }} ">Grab a
            copy!</a>
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
            <x-markdown>
                {!! $post->getContents() !!}
            </x-markdown>
        </div>

        <br>
        <br>
    </div>
@endsection
