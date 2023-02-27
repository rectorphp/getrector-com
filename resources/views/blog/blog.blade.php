@extends('base')

@section('main')
    <div id="blog">
        <h1 class="main-title">{{ $page_title }}</h1>

        @php /** @var \Rector\Website\Entity\Post[] $posts */ @endphp

        @foreach ($posts as $post)
            <div class="mb-5">
                <h2>
<<<<<<< HEAD
                    <a href="{{ route(\Rector\Website\Enum\RouteName::POST, [
=======
                    <a href="{{ route(\Rector\Website\ValueObject\RouteName::POST, [
>>>>>>> 044a866 (shorter name)
                        'postSlug' => $post->getSlug(),
                    ]) }}">
                        {{ $post->getTitle() }}
                    </a>
                </h2>

                <div class="mt-2 text-grey">
                    <time datetime="{{ $post->getDateTime()->format('Y-m-D') }}">
                        {{ $post->getDateTime()->format('Y-m-d') }}
                    </time>
                </div>

                <br>
            </div>
        @endforeach
    </div>
@endsection
