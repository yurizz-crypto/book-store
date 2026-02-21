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
                    @endphp

                    @if(Auth::user()->isAdmin())
                        <div class="hidden lg:flex items-center bg-[#0046FF]/30 rounded-2xl px-2 py-1 mr-2 border border-[#F5F1DC]/20">
                            <a href="{{ route('admin.books.create') }}" class="p-2 hover:text-[#FF8040]" title="Add Book">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>
                    @endif

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
                        
                        <a href="{{ route('profile.edit') }}" class="h-10 w-10 rounded-xl bg-[#F5F1DC] flex items-center justify-center text-[#001BB7] font-black shadow-inner">
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