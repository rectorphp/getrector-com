@extends('base')

@section('main')
    <div class="mt-5 mb-5">
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

            <p>{{ $ruleMetadata->getDescription() }}</p>

            </div>

            <div class="row mt-3 mb-2">
                <div class="col-12 filter-code-sample">
                    <pre><code class="language-diff">{{ $ruleMetadata->getDiffCodeSample() }}</code></pre>
                </div>

                <div class="col-12 mt-4 mb-3">
                    @if (! $ruleMetadata->isConfigurable())
                    <pre><code class="language-php">// rector.php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(\{{ $ruleMetadata->getRectorClass() }}::class, [
        // constant here :)
    ]);</code></pre>
        @else
<pre><code class="language-php">// rector.php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        \{{ $ruleMetadata->getRectorClass() }}::class,
    ]);</code></pre>
                    @endif
                </div>

                @if ($ruleMetadata->getSets())
                    <div class="col-12 mb-1">
                        SETS:&nbsp;

                        @foreach ($ruleMetadata->getSets() as $set)
                            <span class="badge bg-danger">{{ $set }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
@endsection
