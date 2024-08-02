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

            @include('_snippets/demo/learn_ast_link')

            <p>
                Run Rector on your code to see what it can do for you:
            </p>

            @include('_snippets.form.form_diff')

            @include('_snippets.form.form_textarea', [
                'label' => 'PHP snippet to change',
                'inputName' => 'php_contents',
                'defaultValue' => $rectorRun->getContent()
            ])

            <div class="clearfix mb-3" style="clear: both"></div>

            @if ($rectorRun->isSuccessful() && $rectorRun->getAppliedRules())
                <div class="row">
                    <div class="pt-0 pb-4 col-12 col-sm-6">
                        <p class="mb-2">Applied Rules:</p>

                        <ul class="list-noindent">
                            @foreach ($rectorRun->getAppliedRules() as $applied_rule)
                                @php
                                    /** @var $applied_rule \App\ValueObject\AppliedRule */
                                @endphp

                                <li>
                                    <a href="{{ $applied_rule->getGitHubReadmeLink() }}">
                                        {{ $applied_rule->getShortClass() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="pt-0 pb-4 col-12 col-sm-6">
                        <p class="mb-2">Is the result wrong?</p>
                        <a href="{{ issueLink($rectorRun) }}" class="btn btn-danger">Create an
                            issue</a>

                        @if ($rectorRun->canCreateFixture())
                            <a href="{{ pullRequestLink($rectorRun) }}"
                               class="btn btn-primary ms-3">Create
                                a Test</a>
                        @endif
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
