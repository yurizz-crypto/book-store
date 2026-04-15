@extends('layouts.app')

@section('title', 'Admin Dashboard - PageTurner')

@section('header')
    <div class="flex items-center justify-between w-full">
        <span class="font-black text-[20px] tracking-tighter uppercase text-[#001BB7]">Admin Control Center</span>
        <div class="bg-[#001BB7] text-white font-black px-4 py-2 rounded-lg uppercase tracking-widest text-[9px] shadow-sm">
            System Administrator
        </div>
    </div>
@endsection

@section('content')
<div class="pb-10 pt-2 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach([
            ['Add Book', 'admin.books.create', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['Add Category', 'admin.categories.create', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ['Orders', 'admin.orders.index', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['Settings', 'profile.edit', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z']
        ] as [$label, $route, $path])
        <a href="{{ route($route) }}" class="group block p-8 bg-white rounded-[2.5rem] border border-[#0046FF]/5 hover:border-[#FF8040]/30 hover:scale-[1.05] transition-all duration-300 shadow-xl shadow-[#0046FF]/5 text-center">
            <div class="text-[#0046FF] mb-3 group-hover:text-[#FF8040] group-hover:scale-110 transition-all duration-300">
                <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                </svg>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.2em] text-[#001BB7] group-hover:text-[#FF8040] transition-colors">{{ $label }}</span>
        </a>
        @endforeach
    </div>

    <div class="bg-gradient-to-r from-white to-[#F5F1DC]/30 p-8 rounded-[2.5rem] border border-[#0046FF]/5 shadow-xl flex flex-col lg:flex-row justify-between items-center gap-6">
        <div class="flex-1">
            <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter">Data Management</h2>
            <p class="text-xs font-bold text-[#0046FF]/60 tracking-wide mt-1">Import and export system records for compliance and offline analysis.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <form action="{{ route('admin.import.books') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-[#0046FF]/10 shadow-sm">
                @csrf
                <input type="file" name="import_file" accept=".xlsx,.csv" required class="text-xs font-bold text-[#001BB7] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#001BB7]/10 file:text-[#001BB7] hover:file:bg-[#001BB7]/20 transition-all cursor-pointer">
                <button type="submit" class="bg-[#001BB7] text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-[#0046FF] transition-all active:scale-95">
                    Import
                </button>
            </form>

            <span class="text-[#001BB7]/20 font-black">|</span>

            <a href="{{ route('admin.export.books') }}" class="inline-flex items-center gap-2 bg-emerald-500 text-white px-8 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/30 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
            </a>
        </div>
    </div>
    
    @include('admin.dashboard.partials._financials', [
        'revenue' => $stats['revenue'], 
        'avgOrder' => $stats['avgOrder']
    ])

    @include('admin.dashboard.partials._rankings', [
        'topBooks' => $topBooks, 
        'topCategories' => $topCategories
    ])

    @include('admin.dashboard.partials._inventory_warning', ['books' => $lowStock])

    <div class="bg-white p-10 rounded-[3rem] border border-[#0046FF]/5 shadow-2xl">
        <h2 class="text-2xl font-black text-[#001BB7] uppercase tracking-tighter mb-8 flex items-center gap-3">
            <div class="w-2 h-8 bg-[#FF8040] rounded-full"></div>
            User Growth Trends (7 Days)
        </h2>
        <div class="h-[300px]">
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="space-y-6">
            @foreach([
                ['Readers', $stats['users'], 'bg-[#001BB7]'],
                ['Catalogue', $stats['books'], 'bg-[#FF8040]'],
                ['System Orders', $stats['orders'], 'bg-emerald-500']
            ] as [$label, $count, $color])
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#0046FF]/5 shadow-xl flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#001BB7]/40">{{ $label }}</span>
                    <h3 class="text-4xl font-black text-[#001BB7] mt-1 tracking-tighter">{{ number_format($count) }}</h3>
                </div>
                <div class="w-12 h-12 {{ $color }} rounded-2xl shadow-lg opacity-80"></div>
            </div>
            @endforeach
        </div>

        <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-[#0046FF]/5 shadow-2xl min-h-[450px]">
            <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter mb-6">Fulfillment Distribution</h2>
            <div class="relative h-[320px] w-full flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-10 rounded-[3rem] border border-[#0046FF]/5 shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter">Latest System Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-[#FF8040] uppercase tracking-widest border-b-2 border-[#FF8040]/20">View All</a>
            </div>
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block group">
                    <div class="flex items-center justify-between p-5 bg-[#F5F1DC]/30 rounded-2xl border border-transparent group-hover:border-[#0046FF]/10 group-hover:scale-[1.02] transition-all duration-300">
                        <div>
                            <p class="font-black text-[#001BB7] text-sm">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                            <p class="text-[10px] font-bold text-[#0046FF]/40 uppercase tracking-widest">#{{ $order->id }} • {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-4 py-1.5 bg-white rounded-xl text-[9px] font-black uppercase text-[#001BB7] border border-[#001BB7]/10">{{ $order->status }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-10 rounded-[3rem] border border-[#0046FF]/5 shadow-2xl">
            <h2 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter mb-8">Recent Feedback</h2>
            <div class="space-y-4">
                @foreach($recentReviews as $review)
                <a href="{{ route('books.show', $review->book) }}" class="block group">
                    <div class="p-6 bg-[#F5F1DC]/30 rounded-2xl border border-transparent group-hover:border-[#0046FF]/10 group-hover:scale-[1.02] transition-all duration-300 flex items-start gap-4">
                        <div class="w-10 h-10 bg-[#001BB7] rounded-xl flex items-center justify-center text-[#F5F1DC] font-black text-xs shrink-0 shadow-sm">
                            {{ substr($review->user->first_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-[#001BB7] mb-1 group-hover:text-[#FF8040] transition-colors">{{ $review->user->first_name }} on {{ $review->book->title }}</p>
                            <p class="text-sm italic text-[#001BB7]/70 font-bold line-clamp-1">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('userChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($userGrowth->pluck('date')) !!},
            datasets: [{
                label: 'New Registrations',
                data: {!! json_encode($userGrowth->pluck('total')) !!},
                borderColor: '#FF8040',
                backgroundColor: 'rgba(255, 128, 64, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 4,
                pointRadius: 6,
                pointBackgroundColor: '#FF8040',
                pointBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    grid: { display: false }, 
                    ticks: { 
                        font: { weight: 'bold' },
                        stepSize: 1,
                        precision: 0,
                        callback: function(value) { if (Math.floor(value) === value) return value; }
                    } 
                },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusSummary->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($statusSummary->pluck('total')) !!},
                backgroundColor: ['#001BB7', '#FF8040', '#0046FF', '#10b981'],
                borderWidth: 0,
                hoverOffset: 25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 20 },
            cutout: '80%',
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { boxWidth: 12, usePointStyle: true, font: { weight: 'bold', size: 11 }, padding: 25 } 
                } 
            }
        }
    });
</script>
@endsection