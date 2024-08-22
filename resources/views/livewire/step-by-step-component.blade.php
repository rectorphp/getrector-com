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
            </div>

        </div>

        <br>
        <br>

        <div class="card">
            <div class="card-body">
                <button onclick="copyConfig(event)" class="btn btn-sm btn-primary float-end" id="copyButton">
                    <em class="fas fa-fw fa-copy"></em>
                    &nbsp;
                    Copy code
                </button>

                <p>
                    Optimal contents of <code>rector.php</code>
                </p>

                <pre class="mt-4 mb-1"><code class="language-php" style="min-height: 24em" id="configContents">{!! $rectorConfigContents !!}</code></pre>
            </div>
        </div>
    </form>

    <script>
        function copyConfig(event) {
            // Prevent the link from redirecting
            event.preventDefault();

            // Get the content of the <code> element inside the <pre> element
            var content = document.getElementById("configContents").textContent;

            // Create a temporary textarea element to hold the code
            var tempTextarea = document.createElement("textarea");
            tempTextarea.value = content;

            // Add the textarea to the document
            document.body.appendChild(tempTextarea);

            // Select the content of the textarea
            tempTextarea.select();

            // Copy the selected text
            document.execCommand("copy");

            // Remove the temporary textarea element from the document
            document.body.removeChild(tempTextarea);

            // Get the button element
            var button = document.getElementById("copyButton");

            // Change the button text to "Copied"
             button.innerHTML = '<em class="fas fa-check fa-fw"></em>&nbsp;Copied';

            // After 3 seconds, change the text back to "Copy Link"
            setTimeout(function() {
                 button.innerHTML = '<em class="fas fa-copy fa-fw"></em>&nbsp;Copy code';
            }, 2000); // 2 seconds
        }

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


        function handleKeyPress(event) {
            if (event.key === 'ArrowLeft') {
                window.Livewire.dispatch('{{ \App\Enum\ComponentEvent::PREVIOUS_STEP }}');
            } else if (event.key === 'ArrowRight') {
                window.Livewire.dispatch('{{ \App\Enum\ComponentEvent::NEXT_STEP }}');
            }
        }

        document.addEventListener('keydown', handleKeyPress);
    </script>
</div>
