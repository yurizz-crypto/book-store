<nav class="bg-[#001BB7] text-[#F5F1DC] shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            {{-- Left Side --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <h3 class="text-2xl font-black text-[#F5F1DC] tracking-tighter uppercase">
                        PageTurner<span class="text-[#FF8040]">.</span>
                    </h3>
                </a>

                <div class="hidden md:flex ml-10 space-x-1">
                    <a href="{{ route('home') }}" 
                       class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-[#0046FF] transition-all {{ request()->routeIs('home') ? 'bg-[#0046FF]' : '' }}">Home</a>
                    <a href="{{ route('books.index') }}" 
                       class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-[#0046FF] transition-all {{ request()->routeIs('books.*') ? 'bg-[#0046FF]' : '' }}">Books</a>
                    <a href="{{ route('categories.index') }}" 
                       class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-[#0046FF] transition-all {{ request()->routeIs('categories.*') ? 'bg-[#0046FF]' : '' }}">Categories</a>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest px-5 py-2 hover:text-[#FF8040] transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-[#F5F1DC] text-[#001BB7] px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-widest shadow-lg hover:bg-white transition active:scale-95">Register</a>
                @endguest

                @auth
                    @php
                        if(Auth::user()->isAdmin()) {
                            $orderCount = \App\Models\Order::where('status', 'pending')->count();
                            $iconRoute = route('admin.orders.index', ['status' => 'pending']);
                        } else {
                            $activeCart = Auth::user()->orders()->where('status', 'cart')->first();
                            $orderCount = $activeCart ? $activeCart->orderItems()->sum('quantity') : 0;
                            $iconRoute = route('orders.index', ['status' => 'cart']);
                        }

                        $unreadNotifications = Auth::user()->unreadNotifications;
                        $unreadCount = $unreadNotifications->count();
                    @endphp

                    @if(Auth::user()->isAdmin())
                        <div class="hidden lg:flex items-center bg-[#0046FF]/30 rounded-2xl px-2 py-1 mr-2 border border-[#F5F1DC]/20">
                            <a href="{{ route('admin.books.create') }}" class="p-2 hover:text-[#FF8040]" title="Add Book">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>

                        <div class="hidden lg:flex items-center bg-[#0046FF]/30 rounded-2xl px-2 py-1 mr-2 border border-[#F5F1DC]/20 hover:bg-[#0046FF]">
                            <a href="{{ route('admin.audits.index') }}" class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl transition-all">
                                Audit Logs
                            </a>
                        </div>

                    @endif

                    <div class="relative group flex items-center h-full">
                        <button class="relative p-2 hover:bg-[#0046FF] rounded-2xl transition group flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-5 w-5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF8040] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-5 w-5 bg-[#FF8040] text-[10px] font-black text-white items-center justify-center shadow-sm">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                </span>
                            @endif
                        </button>

                        <div class="absolute right-0 top-[60px] w-80 bg-[#F5F1DC] rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-[#001BB7]/10 overflow-hidden">
                            <div class="bg-[#0046FF] px-4 py-3 flex justify-between items-center text-white">
                                <span class="font-bold text-sm tracking-widest uppercase">Notifications</span>
                                @if($unreadCount > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs hover:text-[#FF8040] font-semibold transition">Mark all read</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <a href="{{ route('notifications.click', $notification->id) }}" class="block px-4 py-3 hover:bg-[#F5F1DC]/50 border-b border-[#001BB7]/5 transition-colors">
                                    
                                    {{-- 1. Dynamic Title --}}
                                    <p class="text-xs font-black uppercase tracking-widest {{ isset($notification->data['title']) && str_contains($notification->data['title'], 'Critical') ? 'text-red-600' : 'text-[#001BB7]' }}">
                                        {{ $notification->data['title'] ?? $notification->data['alert'] ?? 'System Notification' }}
                                    </p>

                                    @if(isset($notification->data['details']['event']))
                                        <div class="mt-1 text-[10px] text-gray-600 font-medium space-y-0.5">
                                            <p><span class="font-bold text-[#001BB7]">Event:</span> {{ $notification->data['details']['event'] }}</p>
                                            
                                            {{-- Only show Target if it exists --}}
                                            @isset($notification->data['details']['target'])
                                                <p><span class="font-bold">Target:</span> {{ $notification->data['details']['target'] }}</p>
                                            @endisset

                                            {{-- Only show IP if it exists (Security Alerts) --}}
                                            @isset($notification->data['details']['ip'])
                                                <p><span class="font-bold text-red-500">IP:</span> {{ $notification->data['details']['ip'] }}</p>
                                            @endisset
                                        </div>
                                    @endif

                                    {{-- 3. Fallback for Standard Alerts (like your Backup notification) --}}
                                    @if(isset($notification->data['message']) || isset($notification->data['alert']))
                                        <p class="mt-1 text-[10px] text-gray-500 font-medium">
                                            {{ $notification->data['message'] ?? $notification->data['alert'] ?? '' }}
                                        </p>
                                    @endif

                                    {{-- Timestamp --}}
                                    <p class="text-[9px] text-[#FF8040] font-black uppercase mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center">
                                    <p class="text-xs font-bold text-[#001BB7]/40 uppercase tracking-widest">No new notifications</p>
                                </div>
                            @endforelse
                            </div>
                        </div>
                    </div>
                    <a href="{{ $iconRoute }}" 
                       class="relative p-2 hover:bg-[#0046FF] rounded-2xl transition group flex items-center justify-center">
                        @if(Auth::user()->isAdmin())
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @endif

                        @if($orderCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF8040] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-5 w-5 bg-[#FF8040] text-[10px] font-black text-white items-center justify-center shadow-sm">
                                    {{ $orderCount > 99 ? '99+' : $orderCount }}
                                </span>
                            </span>
                        @endif
                    </a>

                    <div class="flex items-center gap-3 pl-4 border-l border-[#F5F1DC]/30">
                        <div class="hidden flex-col items-end md:flex">
                            <span class="text-xs font-black uppercase tracking-widest text-[#F5F1DC]">{{ Auth::user()->first_name }}</span>
                            <span class="text-[10px] text-[#FF8040] font-bold uppercase tracking-tighter">{{ Auth::user()->role }}</span>
                        </div>
                        
                        <a href="{{ route('profile.edit') }}" class="h-10 w-10 rounded-xl bg-[#F5F1DC] flex items-center justify-center text-[#001BB7] font-black shadow-inner hover:bg-white transition">
                            {{ substr(Auth::user()->first_name, 0, 1) }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 hover:text-[#FF8040] transition" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>