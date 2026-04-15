@extends('layouts.app')

@section('title', 'Too Many Requests - PageTurner')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center bg-white p-16 rounded-[3rem] shadow-2xl border border-[#FF8040]/20 max-w-lg">
        <div class="w-24 h-24 bg-[#FF8040]/10 text-[#FF8040] rounded-3xl flex items-center justify-center mx-auto mb-8 rotate-12">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase mb-4">Whoa There!</h1>
        <p class="text-lg font-bold text-[#001BB7]/60 mb-8">{{ $message ?? 'You are sending too many requests. Please wait a minute before trying again.' }}</p>
        <a href="{{ route('home') }}" class="inline-block bg-[#001BB7] text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#0046FF] transition-all shadow-xl">
            Return to Homepage
        </a>
    </div>
</div>
@endsection