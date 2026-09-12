@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <span class=" text-danger">{{ $message }}</span>
    @endforeach
@endif
