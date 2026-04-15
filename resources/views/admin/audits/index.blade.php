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
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter">Recent Activity</h2>
            
            <form action="{{ route('admin.audits.index') }}" method="GET" class="flex gap-2">
                <select name="event" class="rounded-xl border-[#001BB7]/20 text-sm font-bold text-[#001BB7] focus:ring-[#FF8040] focus:border-[#FF8040]">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
                <button type="submit" class="bg-[#001BB7] text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-[#0046FF] transition">Filter</button>
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