@extends('base')

@php /** @var $\Rector\Website\Blog\ValueObject\Post post */ @endphp
@php $page_title = '{{ $post->title }}'; @endphp

@section('main')
    <div class="alert alert-info mt-3 mb-5" role="alert">
        Would you like to <strong>learn Rector in depth</strong>?
        The <strong>Rector - The Power of Automated Refactoring</strong> book is out now.
        <a href="https://leanpub.com/rector-the-power-of-automated-refactoring?utm_source=getrector_post_detail">Grab a copy!</a>
    </div>

    <div id="post">
        <div class="mt-3">
            <time datetime="{{ $post->dateTime->format('Y-m-D') }}" class="text-grey">
                {{ $post->dateTime->format() }}
            </time>
        </div>

        <h1>{{ $post->title }}</h1>

        @if (post.sinceRector)
            <div class="alert alert-warning">
                This feature is available since <strong>Rector {{ $post->sinceRector }}</strong>.
            </div>
        @endif

        @if (post.deprecated)
            <div class="card border-danger card-bigger mt-5">
                <div class="card-header text-white bg-danger">
                    <strong>{{ $post->deprecatedSince->format("F Y") }} Update</strong>
                </div>
                @if (post.deprecatedMessage is not null)
                    <div class="card-body pb-2">
                        {{ post.deprecatedMessage|markdown|raw }}
                    </div>
                @endif
            </div>

            <br>
        {% elseif post.updated %}
            <div class="card border-success card-bigger mt-5">
                <div class="card-header text-white bg-success">
                    <strong>{{ $post->updatedSince->format("F Y") }} Update</strong>
                </div>
                @if (post.updatedMessage is not null)
                    <div class="card-body pb-2">
                        {{ post.updatedMessage|markdown|raw }}
                    </div>
                @endif
            </div>

            <br>
        @endif


        <div class="perex">{{ post.perex|markdown|raw }}</div>

        <div class="text-body">
            {{ post.htmlContent|raw }}
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
