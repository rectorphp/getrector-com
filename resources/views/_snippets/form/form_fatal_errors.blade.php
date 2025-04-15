@if ($rectorRun->hasRun() && $rectorRun->isSuccessful() !== true)
    <div class="row">
        <div class="alert alert-danger mb-3">
            <p>
                <strong>Rector run Failed:</strong>
            </p>

            {!! $rectorRun->getFatalErrorMessage() !!}

            @if ($rectorRun->getErrors() !== [])
                <ul class="mt-3 ms-0 pl-4">
                    @foreach ($rectorRun->getErrors() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endif
