@extends('base')

@php $page_title = 'Do you Want to Learn&nbsp;about&nbsp;Rector?'; @endphp

@section('main')
    <div id="blog">
        <h1 class="main-title">Read about Rector</h1>

        @foreach ($posts as $post)
            <div class="mb-5">
                <h2>
                    <a href="{{ route('post', {'postSlug': post.slug }) }}">{{ $post->title }}</a>
                </h2>

                <div class="mt-2 text-grey">
                    <time datetime="{{ $post->dateTime->format('Y-m-D') }}">
                        {{ $post->dateTime->format() }}
                    </time>
                </div>

                <br>
            </div>
        @endforeach
        </div>
    </div>
@endsection
