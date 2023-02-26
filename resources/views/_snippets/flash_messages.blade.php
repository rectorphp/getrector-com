@foreach ($application.flashes as $type, messages)
    @foreach ($messages as $message)
        <div class="alert alert-{{ $type }} mt-4" role="alert">
            {{ $message }}
        </div>
    @endforeach
@endforeach
