@extends('base')

@php
    use App\Utils\RectorMetadata;

    /** @var $rectorRun \App\Entity\RectorRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form
            action="{{ action(\App\Controller\Demo\ProcessDemoFormController::class) }}"
            method="post"
            class="mb-5"
        >

            @csrf <!-- {{ csrf_field() }} -->

            @include('_snippets/form/form_fatal_errors')

            <p>
                Run Rector on your code to see what it can do for you:
            </p>

            @if ($rectorRun->isSuccessful())
                @include('_snippets.form.tabbed_code_and_diff', [
                    'inputName' => 'php_contents',
                    'rectorRun' => $rectorRun
                ])
            @else
                @include('_snippets.form.form_textarea', [
                    'label' => 'PHP snippet to change',
                    'inputName' => 'php_contents',
                    'defaultValue' => $rectorRun->getContent()
                ])
            @endif

            <div class="clearfix pb-0 mb-0" style="clear: both"></div>

            @if ($rectorRun->isSuccessful() && $rectorRun->getAppliedRules())
                <div class="row">
                    <div class="pt-0 pb-2 col-12 col-sm-6">
                        <p class="mb-2">Applied Rules:</p>

                        <ul class="list-noindent">
                            @foreach ($rectorRun->getAppliedRules() as $appliedRule)
                                <li>
                                    <a href="{{ action(\App\Controller\RuleDetailController::class, ['slug' => $appliedRule->getSlug()]) }}">
                                        {{ $appliedRule->getShortClass() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    <p class="mt-4 mb-2">Not a change you expect?</p>

                    <ul>
                        <li>
                            <a href="{{ issueLink($rectorRun) }}" class="text-danger">Create an issue</a> on GitHub
                        </li>

                        @if ($rectorRun->canCreateFixture())
                            <li>...or to speed up fix &ndash; <a href="{{ pullRequestLink($rectorRun) }}"  class="text-success">add a test fixture</a>
                            </li>
                        @endif
                    </ul>
                    </div>
                </div>
            @endif

            @include('_snippets.form.form_textarea', [
                'label' => 'Config&nbsp;&nbsp;<code>rector.php</code>',
                'inputName' => 'runnable_contents',
                'defaultValue' => $rectorRun->getRunnablePhp()
            ])

            <div class="row">
                <div class="col-6 mt-4 mb-5">
                    <button type="submit" id="demo_form_process" name="process"
                            class="btn btn-lg btn-success m-auto btn-demo-submit">Run Rector
                    </button>
                </div>

                <div class="col-6 text-end text-secondary" id="rector_version">
                    Rector version:
                    <a href="https://github.com/rectorphp/rector-src/commit/{{ RectorMetadata::getReleaseVersion() }}">{{ RectorMetadata::getReleaseVersion() }}</a>
                    - released at {{ RectorMetadata::getReleaseDate() }}
                </div>
            </div>

        </form>
    </div>
@endsection
