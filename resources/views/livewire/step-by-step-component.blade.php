<div>
    <form>
        <div class="row">
            <div class="form-group col-4">
                <span class="me-2">Starting PHP version:</span>

                <select class="form-select d-inline" style="max-width: 6em"
                        wire:model.live="startingPhpVersion">
                    <option value="5.3">5.3</option>
                    <option value="5.4">5.4</option>
                    <option value="5.5">5.5</option>
                    <option value="5.6">5.6</option>
                    <option value="7.0">7.0</option>
                    <option value="7.1">7.1</option>
                    <option value="7.2">7.2</option>
                    <option value="7.3">7.3</option>
                    <option value="7.4">7.4</option>
                    <option value="8.0">8.0</option>
                    <option value="8.1">8.1</option>
                    <option value="8.2">8.2</option>
                    <option value="8.3">8.3</option>
                </select>

            </div>

            <div class="form-group col-4">
                <div class="me-3 mb-2">
                    Step: {{ $step }}
                </div>

                <style>
                    input[type="range"] {
                        width: 200px;
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
                    }
                </style>

                <input type="range" class="form-range"  min="0" max="{{ $stepCount }}" value="0" wire:model.live="step">
            </div>

        </div>

        <br>
        <br>

        <div class="card">
            <div class="card-body">
                <p>
                    Optimal contents of <code>rector.php</code>
                </p>

                <pre class="mt-4 mb-1"><code class="language-php" style="min-height: 20em">{{ $rectorConfigContents }}</code></pre>
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
