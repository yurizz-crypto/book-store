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

    @include('admin.dashboard.partials._intellegence-widgets')

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

    <div class="mt-12 space-y-6">
        <div class="flex items-center justify-between px-2">
            <h2 class="text-2xl font-black text-[#001BB7] uppercase tracking-tighter">System & Data Operations</h2>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            
            {{-- Card 1: Book Data & Backups --}}
            <div class="bg-white rounded-[2.5rem] border border-[#0046FF]/10 shadow-xl shadow-[#0046FF]/5 overflow-hidden flex flex-col transition-all hover:shadow-2xl hover:shadow-[#0046FF]/10">
                <div class="p-8 bg-gradient-to-br from-white to-[#F5F1DC]/40 border-b border-[#0046FF]/5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#001BB7] text-white rounded-2xl shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-[#001BB7] uppercase tracking-tight">Book Data & Backups</h3>
                            <p class="text-xs font-bold text-[#0046FF]/60">Manage catalog records and system snapshots.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-8 flex-1 bg-white">
                    {{-- Book Import --}}
                    <div>
                        <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-3">Import Catalog</label>
                        <form id="importForm" action="{{ route('admin.import.books') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                            @csrf
                                                        
                            @if ($errors->has('import_file'))
                                <div class="text-red-500 text-xs font-bold mt-2">
                                    {{ $errors->first('import_file') }}
                                </div>
                            @endif
                            
                            <input type="file" name="import_file" accept=".xlsx,.csv" required class="flex-1 text-xs font-bold text-gray-600 bg-gray-50 border border-gray-200 rounded-xl file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#001BB7]/10 file:text-[#001BB7] hover:file:bg-[#001BB7]/20 transition-all cursor-pointer">
                            <button type="submit" class="bg-[#001BB7] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-[#0046FF] transition-all shadow-md active:scale-95 text-center">
                                Upload
                            </button>
                        </form>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Book Export & System Backup --}}
                    <div class="flex flex-wrap items-end justify-between gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-3">Export Records</label>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="document.getElementById('exportModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-emerald-500 text-white px-6 py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-md shadow-emerald-500/20 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Export Data
                                </button>
                                <a href="{{ route('admin.import.template') }}" class="text-gray-400 hover:text-[#001BB7] text-[10px] font-black uppercase tracking-widest underline transition-all">
                                    Get Template
                                </a>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">System Health</label>
                            <form action="{{ route('admin.backup.run') }}" method="POST" onsubmit="return confirm('This will pause system performance for a moment. Run full backup now?');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-md active:scale-95">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Run Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-[2.5rem] border border-[#0046FF]/10 shadow-xl shadow-[#0046FF]/5 overflow-hidden flex flex-col transition-all hover:shadow-2xl hover:shadow-[#0046FF]/10">
                <div class="p-8 bg-gradient-to-br from-white to-blue-50/50 border-b border-[#0046FF]/5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#0046FF] text-white rounded-2xl shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-[#001BB7] uppercase tracking-tight">Corporate Users</h3>
                            <p class="text-xs font-bold text-[#0046FF]/60">Bulk create institutional accounts (GDPR Ready).</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-8 flex-1 bg-white">
                    <div>
                        <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-3">Bulk User Import</label>
                        <form action="{{ route('admin.import.users') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                            @csrf

                            @if ($errors->has('import_file'))
                                <div class="text-red-500 text-xs font-bold mt-2">
                                    {{ $errors->first('import_file') }}
                                </div>
                            @endif
                            
                            {{-- NEW: Role Selector Dropdown --}}
                            <select name="default_role" class="w-full text-xs font-bold text-[#001BB7] bg-[#001BB7]/5 border-none rounded-xl focus:ring-0 cursor-pointer py-3 px-4">
                                <option value="customer">Import as Customers</option>
                                <option value="admin">Import as Admins</option>
                            </select>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="file" name="import_file" accept=".xlsx,.csv" required class="flex-1 text-xs font-bold text-gray-600 bg-gray-50 border border-gray-200 rounded-xl file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#001BB7]/10 file:text-[#001BB7] hover:file:bg-[#001BB7]/20 transition-all cursor-pointer">
                                <button type="submit" class="bg-[#001BB7] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-[#0046FF] transition-all shadow-md active:scale-95 whitespace-nowrap">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr class="border-gray-100">

                    {{-- User Export & GDPR --}}
                    <div>
                        <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-3">Compliance & Export</label>
                        <form action="{{ route('admin.export.users') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <label class="flex items-center gap-3 cursor-pointer group flex-1">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="redact_pii" value="1" checked class="w-5 h-5 rounded text-emerald-500 focus:ring-emerald-500 border-gray-300 shadow-sm cursor-pointer transition-all">
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black uppercase tracking-[0.1em] text-gray-700 group-hover:text-emerald-600 transition-colors">GDPR PII Redaction</span>
                                    <span class="block text-[9px] font-bold text-gray-400 mt-0.5">Masks emails and names in export.</span>
                                </div>
                            </label>
                            
                            <button type="submit" class="inline-flex justify-center items-center gap-2 bg-emerald-500 text-white px-6 py-3.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-md shadow-emerald-500/20 active:scale-95 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Export Users
                            </button>
                        </form>
                    </div>
                </div>
            </div>

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
                <a href="{{ route('admin.orders.index', $order) }}" class="block group">
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
            
            @if($recentReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($recentReviews as $review)
                    <a href="{{ route('books.show', $review->book) }}" class="block group">
                        <div class="p-6 bg-[#F5F1DC]/30 rounded-2xl border border-transparent group-hover:border-[#0046FF]/10 group-hover:scale-[1.02] transition-all duration-300 flex items-start gap-4">
                            <div class="w-10 h-10 bg-[#001BB7] rounded-xl flex items-center justify-center text-[#F5F1DC] font-black text-xs shrink-0 shadow-sm">
                                {{ substr($review->user->first_name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[10px] font-black uppercase text-[#001BB7] group-hover:text-[#FF8040] transition-colors">
                                        {{ $review->user->first_name }} on {{ $review->book->title }}
                                    </p>
                                    @if($review->ai_analysis)
                                        <span class="text-[8px] font-black bg-[#FF8040] text-white px-2 py-0.5 rounded-md uppercase tracking-widest">
                                            AI Insight
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-sm italic text-[#001BB7]/70 font-bold line-clamp-1 mb-2">"{{ $review->comment }}"</p>
                                
                                @if($review->ai_analysis)
                                    <div class="p-3 bg-white/60 rounded-xl border border-[#001BB7]/5 text-[11px] font-bold text-[#001BB7]/80 leading-relaxed">
                                        <span class="text-[#FF8040] font-black">Summary:</span> {{ $review->ai_analysis }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 px-6 bg-[#F5F1DC]/20 rounded-2xl border border-dashed border-[#001BB7]/10">
                    <div class="w-16 h-16 bg-[#001BB7]/5 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#001BB7]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-black text-[#001BB7]/60 uppercase tracking-tight mb-1">No reviews yet.</p>
                </div>
            @endif
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

    document.addEventListener('DOMContentLoaded', function () {
        const importForm = document.getElementById('importForm');
        const submitButton = importForm.querySelector('button[type="submit"]');

        importForm.addEventListener('submit', function (e) {
            // 1. Stop the standard page-reloading form submission
            e.preventDefault();

            // 2. Change the button state so the user knows it's uploading
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'Uploading...';
            submitButton.disabled = true;

            // 3. Gather the file data
            const formData = new FormData(importForm);

            // 4. Send the file in the background using Fetch API
            fetch(importForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Tells Laravel this is an AJAX request
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Upload is done! The background job has started.
                    submitButton.innerHTML = 'Upload Complete!';
                    alert(data.message); // Replace this with a nice UI Toast notification if you have one
                    importForm.reset();
                } else {
                    // Handle validation errors
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    alert('Upload failed. Please check the file size and type.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                alert('A network error occurred during upload.');
            });
        });
    });
</script>

    @include('admin.dashboard.partials._export-modal')

@endsection