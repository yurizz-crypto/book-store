@extends('layouts.app')

@section('title', 'New Category - PageTurner')

@section('content')
    <div class="max-w-3xl mx-auto py-12 px-4">
        {{-- Back Link using Electric Blue --}}
        <div class="mb-8">
            <a href="{{ route('categories.index') }}" class="text-[#0046FF] text-[10px] font-black uppercase tracking-[0.2em] hover:text-[#FF8040] transition flex items-center gap-2 group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to Categories
            </a>
        </div>

        <div class="bg-white shadow-2xl shadow-[#001BB7]/5 rounded-[3rem] p-10 border border-[#0046FF]/5 relative overflow-hidden">
            {{-- Aesthetic Brand Accent: Sunset Orange Glow --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-[#FF8040]/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-1 bg-[#FF8040] rounded-full"></div>
                    <h2 class="text-3xl font-black text-[#001BB7] uppercase tracking-tighter">New Category</h2>
                </div>

                <x-category-form :action="route('admin.categories.store')" />
            </div>
        </div>
        
        {{-- Brand Reinforcement --}}
        <div class="mt-12 flex justify-center">
             <div class="flex items-center gap-3 opacity-20">
                <div class="w-2 h-2 rounded-full bg-[#001BB7]"></div>
                <div class="w-2 h-2 rounded-full bg-[#0046FF]"></div>
                <div class="w-2 h-2 rounded-full bg-[#FF8040]"></div>
             </div>
        </div>
    </div>
@endsection