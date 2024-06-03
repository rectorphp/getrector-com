@extends('base')

@section('main')
    <div id="blog">
        <h1 class="main-title">{{ $page_title }}</h1>

        @php /** @var \Rector\Website\Entity\Post[] $posts */ @endphp

        @foreach ($posts as $post)
            <div class="mb-5">
                <h2>
                    <a href="{{ action(\Rector\Website\Controller\Blog\PostController::class, [
                        'postSlug' => $post->getSlug(),
                    ]) }}">
                        {{ $post->getTitle() }}
                    </a>
                </h2>

                <div class="mt-2 text-secondary">
                    <time datetime="{{ $post->getDateTime()->format('Y-m-D') }}">
                        {{ $post->getDateTime()->format('Y-m-d') }}
                    </time>
                </div>

                <br>
            </div>
        @endforeach
    </div>
@endsection
