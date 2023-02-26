{% extends 'base.twig' %}

{% set page_title %}Do you Want to Learn&nbsp;about&nbsp;Rector?{% endset %}

{% block main %}
    <div id="blog">
        <h1 class="main-title">Read about Rector</h1>

        {% for post in posts %}
        <div class="mb-5">
            <h2>
                <a href="{{ path('post', {'postSlug': post.slug }) }}">{{ post.title }}</a>
            </h2>

            <div class="mt-2 text-grey">
                <time datetime="{{ post.dateTime|date('Y-m-D') }}">
                    {{ post.dateTime|date }}
                </time>
            </div>

            <br>
        </div>
        {% endfor %}
    </div>
</div>
{% endblock %}
-----
@extends('base')

{% set page_title %}Do you Want to Learn&nbsp;about&nbsp;Rector?{% endset %}

@section('main')
    <div id="blog">
        <h1 class="main-title">Read about Rector</h1>

        @foreach ($posts as $post)
        <div class="mb-5">
            <h2>
                <a href="{{ route('post', {'postSlug': post.slug }) }}">{{ $post->title }}</a>
            </h2>

            <div class="mt-2 text-grey">
                <time datetime="{{ post.dateTime|date('Y-m-D') }}">
                    {{ post.dateTime|date }}
                </time>
            </div>

            <br>
        </div>
        @endforeach
    </div>
</div>
@endsection
