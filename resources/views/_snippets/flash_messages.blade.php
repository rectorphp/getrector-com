{{-- colorful flashes, see https://stackoverflow.com/a/47363646/1348344 --}}

@foreach ([
    \Rector\Website\Enum\FlashType::ERROR,
    \Rector\Website\Enum\FlashType::WARNING,
    \Rector\Website\Enum\FlashType::SUCCESS
] as $flashMessageType)
    @if(session()->has($flashMessageType))
        <div class="container mt-4">
            <p class="alert alert-{{ $flashMessageType }}">
                {!! session()->get($flashMessageType) !!}
            </p>
        </div>
    @endif

@endforeach



@foreach ($app.flashes as $type, messages)
    @foreach ($messages as $message)
        <div class="alert alert-{{ $type }} mt-4" role="alert">
            {{ $message }}
        </div>
    @endforeach
@endforeach
