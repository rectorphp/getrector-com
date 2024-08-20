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

            <div class="col-8">
                <div class="d-flex">
                    <button type="button" class="btn  btn-light" wire:click="previousStep">
                        Previous step
                    </button>

                    <div class="progress flex-grow-1 ms-1 me-1 shadow border-light border" role="progressbar" style="height: 3.2em">
                        <div
                            class="progress-bar text-bg-success progress-bar-striped
                            @if ($step < 12)
                            text-dark
                            @else
                            text-white
                            @endif
                            text-bold"
                            style="font-size: 2em; width: {{ $progress }}%;
                        ">
                            {{ $step }}
                        </div>
                    </div>

                    <button type="button" class="btn  btn-success d-inline-block" wire:click="nextStep">
                        Next step
                    </button>
                </div>

                <div class="mt-2">


                </div>

{{--                <a href=""--}}

{{--                <input type="range" class="form-range"  min="0" max="{{ $stepCount }}" value="0" wire:model.live="step" autofocus>--}}
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
