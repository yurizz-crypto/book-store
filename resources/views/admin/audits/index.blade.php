@extends('layouts.app')

@section('title', 'Audit Logs - Admin Dashboard')

@section('header')
    <div class="flex items-center justify-between w-full">
        <span class="font-black text-[20px] tracking-tighter uppercase text-[#001BB7]">System Audit Logs</span>
    </div>
@endsection

@section('content')
<div class="pb-10 pt-2 space-y-8">
    <div class="bg-white shadow-xl rounded-[2.5rem] border border-[#0046FF]/5 overflow-hidden p-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter">Recent Activity</h2>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.audits.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="inline-flex items-center gap-2 bg-white text-[#001BB7] px-4 py-2 rounded-xl border border-[#001BB7]/20 font-black uppercase tracking-widest text-[10px] hover:bg-[#001BB7] hover:text-white transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export CSV
                </a>
                
                <a href="{{ route('admin.audits.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-4 py-2 rounded-xl border border-red-200 font-black uppercase tracking-widest text-[10px] hover:bg-red-50 hover:border-red-300 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
            </div>
        </div>            

        <form action="{{ route('admin.audits.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8 bg-[#F5F1DC]/30 p-6 rounded-[1.5rem] border border-[#001BB7]/10 shadow-sm">
            {{-- Filter by User --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-[#001BB7] mb-1.5">User</label>
                <input type="text" name="user" value="{{ request('user') }}" placeholder="Name or Email..." class="w-full rounded-xl border-[#001BB7]/20 text-xs font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040] shadow-inner transition-all">
            </div>

            {{-- Filter by Event --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-[#001BB7] mb-1.5">Event Type</label>
                <select name="event" class="w-full rounded-xl border-[#001BB7]/20 text-xs font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040] shadow-inner transition-all">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            {{-- Filter by Model --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-[#001BB7] mb-1.5">Model</label>
                <select name="model" class="w-full rounded-xl border-[#001BB7]/20 text-xs font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040] shadow-inner transition-all">
                    <option value="">All Models</option>
                    <option value="Book" {{ request('model') == 'Book' ? 'selected' : '' }}>Books</option>
                    <option value="Order" {{ request('model') == 'Order' ? 'selected' : '' }}>Orders</option>
                    <option value="User" {{ request('model') == 'User' ? 'selected' : '' }}>Users</option>
                </select>
            </div>

            {{-- Date Range --}}
            <div class="sm:col-span-2 grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-[#001BB7] mb-1.5">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-[#001BB7]/20 text-xs font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040] shadow-inner transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-[#001BB7] mb-1.5">To</label>
                    <div class="flex gap-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-[#001BB7]/20 text-xs font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040] shadow-inner transition-all">
                        <button type="submit" class="flex items-center justify-center bg-[#001BB7] text-white px-4 rounded-xl hover:bg-[#FF8040] hover:shadow-md transition-all shadow-sm" title="Search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F5F1DC]/50 text-[#001BB7] text-[10px] uppercase tracking-widest font-black">
                        <th class="p-4 rounded-tl-2xl">Date</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Target Model</th>
                        <th class="p-4 rounded-tr-2xl">Changes (Old &rarr; New)</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($audits as $audit)
                        <tr class="border-b border-[#001BB7]/10 hover:bg-[#F5F1DC]/20 transition">
                            <td class="p-4 whitespace-nowrap text-[#001BB7]/70 font-semibold text-xs">
                                {{ $audit->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="p-4 font-bold text-[#001BB7]">
                                {{ $audit->user ? $audit->user->first_name . ' ' . $audit->user->last_name : 'System/Guest' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest
                                    {{ $audit->event === 'created' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $audit->event === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $audit->event === 'deleted' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $audit->event }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-semibold text-[#0046FF]">
                                {{ class_basename($audit->auditable_type) }} (ID: {{ $audit->auditable_id }})
                            </td>
                            <td class="p-4 text-xs font-mono bg-gray-50/50">
                                @if(empty($audit->old_values) && empty($audit->new_values))
                                    <span class="text-gray-400 italic">No significant data recorded.</span>
                                @else
                                    <ul class="space-y-1">
                                        @foreach($audit->new_values as $attribute => $newValue)
                                            <li>
                                                <strong class="text-[#001BB7] capitalize">{{ str_replace('_', ' ', $attribute) }}:</strong> 
                                                
                                                @if(isset($audit->old_values[$attribute]))
                                                    <span class="text-red-500 line-through">{{ $audit->old_values[$attribute] }}</span> 
                                                    &rarr; 
                                                @endif
                                                
                                                <span class="text-emerald-600 font-bold">{{ is_array($newValue) ? json_encode($newValue) : $newValue }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#001BB7]/50 font-bold italic">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $audits->links() }}
        </div>
    </div>
</div>
@endsection