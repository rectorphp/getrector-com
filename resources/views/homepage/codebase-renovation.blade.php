@extends('base')

@section('main')
    <div id="simple_page">
        <h1 class="main-title">{{ $page_title }}</h1>

        <h2>PHP Version</h2>

            <div class="row">
                <div class="col-6">

                <h3>Before</h3>

                <p>PHP version is ambiguous, defined in multiple places and with upper bracket.</p>

                <pre><code class="language-json">{
    "requires": {
        "php": "^7.2"
    },
    "config": {
        "platform": {
            "php": "7.3.4"
        }
    }
}</code></pre>
            </div>
            <div class="col-6">
                <h3>After</h3>

                <p>There is single PHP version. The latest available stable version to get the best performance and code quality.</p>

                <pre><code class="language-json">{
    "requires": {
        "php": "{{ latestPhp() }}"
    }
}</code></pre>

            </div>

        </div>

        @include('_snippets/homepage_separator')

        <h2>Static Analysis</h2>

        <div class="row">
            <div class="col-6">
                <h3>Before</h3>

                <p>
                    Multiple static-analysis tools, mutually covering code with the same features, taking time of CI and cost developer attention.
                    <br>
                    Huge baseline files with hundreds ignored errors, that make purpose of analysis unreliable and dull.
                </p>

<pre><code class="language-json">{
    "requires-dev": {
        "phpstan/phpstan": "^1.11",
        "phpmd/phpmd": "^2.13",
        "vimeo/psalm": "^4.30"
    }
}</code></pre>

<br>

<p>
    <code>phpstan.neon</code>
</p>

<pre><code class="language-yaml">includes:
    - phpstan-baseline.neon

parameters:
    level: 3

    paths:
        - src
        - tests
</code></pre>

            </div>

            <div class="col-6">
                <h3>After</h3>

                <p>
                    Simple setup with single `phpstan.neon`. No ignores. The best tool to do the job, with fast parallel run. Custom PHPStan rules to deal with your most common code review reports within your domain.
                </p>

                <br>

<pre><code class="language-json">{
    "requires-dev": {
        "phpstan/phpstan": "^1.11"
    }
}</code></pre>

                <br>
                <br>

<p style="margin-top: 1.2em">
    <code>phpstan.neon</code>
</p>

<pre><code class="language-yaml">parameters:
    level: 8

    paths:
        - src
        - tests
</code></pre>

            </div>
        </div>





    </div>
@endsection
