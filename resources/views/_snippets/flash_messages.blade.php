{{-- colorful flashes, see https://stackoverflow.com/a/47363646/1348344 --}}

@foreach ([
    \App\Enum\FlashType::ERROR,
] as $flashMessageType)
    @if (session()->has($flashMessageType))
        <div class="container mt-4">
            <p class="alert alert-{{ $flashMessageType }}">
                {!! session()->get($flashMessageType) !!}
            </p>
        </div>
    @endif

@endforeach
