@extends('base')

@section('main')
    <div id="filter">
        <div style="float: right" class="mt-0">
            <a href="{{ url()->previous() }}">Back to rule search</a>
        </div>
        <div class="clearfix"></div>

        <div class="mt-3 mb-5">
            <div style="--bs-border-opacity: .5;" class="mb-3 mt-2 card
                @if ($ruleMetadata->isConfigurable())
                    border-primary
                @endif
                "
            >
                <div class="card-body pt-0 pb-3 ps-4 pe-4">

                    <div style="float: right" class="mt-3">
                        @if ($ruleMetadata->isConfigurable())
                            <span class="badge bg-primary">Configurable</span>
                        @endif
                    </div>

                    <h2 class="mb-4 mt-4">{{ $page_title }}</h2>

                <p>{!! $ruleMetadata->getDescription() !!}</p>

                <div class="row mt-3 mb-2">
                    @if ($ruleMetadata->isConfigurable())
                        @foreach ($ruleMetadata->getConfiguredDiffSamples() as $configuredDiffSample)
                            <div class="col-12 filter-code-sample">
                                <pre><code class="language-diff">{{ $configuredDiffSample->getDiffCodeSample() }}</code></pre>
                            </div>

                            <div class="col-12 mt-3 mb-2">
                                <p>Configure your <code>rector.php</code>:</p>

                                <pre><code class="language-php">{{ $configuredDiffSample->getConfiguration() }}</code></pre>
                            </div>

                            @if (!$loop->last)
                                <hr class="mb-5 mt-4">
                            @endif
                        @endforeach
                    @else
                        <!-- not configurable rules -->
                        <div class="col-12 filter-code-sample">
                            <pre><code class="language-diff">{{ $ruleMetadata->getDiffCodeSample() }}</code></pre>
                        </div>

                        <div class="col-12 mt-4 mb-3">
                            <p>Configure your <code>rector.php</code>:</p>

                            <pre><code class="language-php">{{ $ruleMetadata->getConfiguration() }}</code></pre>
                        </div>
                    @endif

                    @if ($ruleMetadata->getSets())
                        <div class="col-12 mt-1 mb-1">
                            SETS:&nbsp;

                            @foreach ($ruleMetadata->getSets() as $set)
                                <span class="badge bg-danger">{{ $set->getName() }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
