<div>
    <form>
        Starting PHP version:

        <div class="form-group">
            <select class="form-select d-inline mt-2" style="max-width: 12em"
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

        <br>

        <div class="form-group">
            Step: {{ $step }}

            <br>

            <style>
                input[type="range"] {
                    width: 200px;
                    background-color: #ddd;
                }
                input[type="range"]::-webkit-slider-thumb {
                    background-color: #4CAF50;
                    border-radius: 50%;
                }
            </style>

            <input type="range" min="0" max="100" value="0" wire:model.live="step">
        </div>

        <br>
        <br>

        <div>
            <pre><code class="language-php">{{ $rectorConfigContents }}</code></pre>
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
