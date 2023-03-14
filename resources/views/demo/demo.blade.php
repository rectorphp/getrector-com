@extends('base')

@php
    /** @var $rector_run \Rector\Website\Entity\RectorRun */
@endphp

@section('main')
    <div id="rector_run_form" class="mt-4 mb-3">
        <form action="{{ action(\App\Http\Controllers\ProcessDemoFormController::class) }}" method="post">

            @if ($rector_run->hasRun() && $rector_run->isSuccessful() !== true)
                <div class="alert alert-danger mb-3">
                    <p>
                        <strong>Rector run Failed:</strong>
                    </p>

                    {!! $rector_run->getFatalErrorMessage() !!}

                    @if ($rector_run->getErrors() !== [])
                        <ul class="mt-3 ms-0 pl-4">
                            @foreach ($rector_run->getErrors() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <p>
                Run Rector on your code to see what it can do for you:
            </p>

            @error('php_contents')
                <div class="alert alert-danger">
                    @foreach ($errors->get('php_contents') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @enderror

            <div class="card mb-4">
                <div class="card-body p-0 mb-0">
                    <textarea name="php_contents" class="codemirror_php"
                              required="required">{{ $rector_run->getContent() }}</textarea>
                </div>
            </div>

            @if ($rector_run->isSuccessful())
                <div class="card bg-warning border-warning mb-3">
                    <div class="card-header text-bold">
                        What did Rector change?
                    </div>

                    <div class="card-body p-0">
                        <textarea class="codemirror_diff">{{ $rector_run->getContentDiff() }}</textarea>
                    </div>
                </div>

                @if ($rector_run->getAppliedRules())
                    <div class="row">
                        <div class="pt-0 pb-4 col-12 col-sm-6">
                            <p class="mb-2">Applied Rules:</p>

                            <ul class="list-noindent">
                                @foreach ($rector_run->getAppliedRules() as $applied_rule)
                                    @php
                                        /** @var $applied_rule \Rector\Website\ValueObject\AppliedRule */
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
                            <a href="{{ issueLink($rector_run) }}" class="btn btn-danger">Create an issue</a>

                            @if ($rector_run->canCreateFixture())
                                <a href="{{ prLink($rector_run) }}" class="btn btn-primary ms-3">Create a Test</a>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            @error('rector_config')
            <div class="alert alert-danger">
                @foreach ($errors->get('rector_config') as $error)
                    {{ $error }}
                @endforeach
            </div>
            @enderror

            <div class="card mb-2">
                <div class="card-header">
                    Config&nbsp;&nbsp;<code>rector.php</code>
                </div>

                <div class="card-body p-0">
                    <textarea name="rector_config" class="codemirror_php"
                              required="required">{{ $rector_run->getConfig() }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-6 mt-4 mb-5">
                    <button type="submit" id="demo_form_process" name="process"
                            class="btn btn-lg btn-success m-auto btn-demo-submit">Process
                    </button>
                </div>

                <div class="col-6 text-end text-secondary" id="rector_version">
                    Rector version:
                    <a href="https://github.com/rectorphp/rector-src/commit/{{ \Rector\Website\Utils\RectorMetadata::getReleaseVersion() }}">{{ \Rector\Website\Utils\RectorMetadata::getReleaseVersion() }}</a>
                    - released at {{ \Rector\Website\Utils\RectorMetadata::getReleaseDate() }}
                </div>
            </div>

        </form>
    </div>
@endsection
