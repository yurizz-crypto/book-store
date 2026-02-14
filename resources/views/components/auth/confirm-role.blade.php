@if (!auth()->user()->isAdmin())
    {{ $slot }}
@endif