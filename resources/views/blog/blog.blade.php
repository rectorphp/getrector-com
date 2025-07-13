@extends('base')

@section('main')
    <div id="blog">
        <h1 style="text-wrap: balance">{{ $page_title }}</h1>

        @php /** @var \App\Entity\Post[] $posts */ @endphp

        @foreach ($posts as $post)
            <div class="mb-4">
                <h2 class="mb-0">
                    <a href="{{ action(\App\Controller\Blog\PostController::class, [
                        'postSlug' => $post->getSlug(),
                    ], false) }}">
                        {{ $post->getTitle() }}
                    </a>
                </h2>

                <div class="text-secondary">
                    <time datetime="{{ $post->getDateTime()->format('Y-m-D') }}">
                        {{ $post->getDateTime()->format('Y-m-d') }}
                    </time>
                </div>
            </div>
        @endforeach
    </div>
@endsection
