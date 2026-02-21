@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-[11px] font-black uppercase tracking-widest text-[#FF8040] space-y-1.5 ml-1 mt-2']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF8040] animate-pulse"></span>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif