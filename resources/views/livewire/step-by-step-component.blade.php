<div>
    <form>
        <div class="row">
            <div class="col-4">
                <span class="me-2">Starting PHP version:</span>

                <select class="form-select d-inline" style="max-width: 6em"
                        wire:model.live="startingPhpVersion">
                    @foreach ($phpVersionOptions as $phpVersionOption)
                        <option value="{{ $phpVersionOption }}">{{ $phpVersionOption }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width: 10%;margin-top: .1em">
                <span class="text-medium">
                    Step: <strong>{{ $step }}</strong>
                </span>
            </div>

            <div class="col-6">
                <style>
                    input[type="range"] {
                        width: 100%;
                    }

                    input[type="range"]::-webkit-slider-thumb {
                        background: green;
                        border-radius: 50%;
                        border: 3px solid white;
                        margin-top:.1em;
                    }

                    input[type="range"]::-webkit-slider-runnable-track {
                        background-color: #4CAF50;
                        height: 1.2rem;
                        margin-top: .55em;
                    }
                </style>

                <input type="range" class="form-range"  min="0" max="{{ $stepCount }}" value="0" wire:model.live="step" autofocus>
            </div>

        </div>

        <br>
        <br>

        <div class="card">
            <div class="card-body">
                <p>
                    Optimal contents of <code>rector.php</code>
                </p>

                <pre class="mt-4 mb-1"><code class="language-php" style="min-height: 23em">{!! $rectorConfigContents !!}</code></pre>
            </div>
        </div>
    </form>

    <script>
        // Listen for events dispatched from Livewire components...
        document.addEventListener('DOMContentLoaded', function () {
            // render event from src/Livewire/RectorFilterComponent.php:12
            document.addEventListener('{{ \App\Enum\ComponentEvent::STEP_CHANGED }}', () => {
                requestAnimationFrame(() => {
                    document.querySelectorAll('pre code.language-php').forEach((element) => {
                        hljs.highlightElement(element);
                    });
                });
            });
        })
    </script>
</div>
