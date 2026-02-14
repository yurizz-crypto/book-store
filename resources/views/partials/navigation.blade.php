<nav class="bg-indigo-600 text-white shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            {{-- Left Side: Brand & Main Nav --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl font-black tracking-tighter uppercase">PageTurner</span>
                </a>

                <div class="hidden md:flex ml-10 space-x-1">
                    <a href="{{ route('home') }}" class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-indigo-500 transition-all {{ request()->routeIs('home') ? 'bg-indigo-700' : '' }}">Home</a>
                    <a href="{{ route('books.index') }}" class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-indigo-500 transition-all {{ request()->routeIs('books.*') ? 'bg-indigo-700' : '' }}">Books</a>
                    <a href="{{ route('categories.index') }}" class="text-sm font-bold uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-indigo-500 transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">Categories</a>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest px-5 py-2 hover:text-indigo-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-widest shadow-lg hover:bg-indigo-50 transition active:scale-95">Register</a>
                @endguest

                @auth
                    {{-- Admin Quick Actions --}}
                    @if(auth()->user()->role === 'admin')
                        <div class="hidden lg:flex items-center bg-indigo-700 rounded-2xl px-2 py-1 mr-2 border border-indigo-400/30">
                            <a href="{{ route('admin.books.create') }}" class="p-2 hover:text-indigo-200" title="Add Book">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>
                    @endif

                    {{-- Cart Button --}}
                    <x-auth.confirm-role>
                        <a href="{{ route('orders.index') }}" class="relative p-3 hover:bg-indigo-500 rounded-xl transition group">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="absolute top-2 right-2 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-300 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-white text-[10px] font-bold text-indigo-600 items-center justify-center">0</span>
                            </span>
                        </a>
                    </x-auth.confirm-role>

                    {{-- User Dropdown Area --}}
                    <div class="flex items-center gap-3 pl-4 border-l border-indigo-400/50">
                        <div class="hidden flex-col items-end md:flex">
                            <span class="text-xs font-black uppercase tracking-widest">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-indigo-200 font-bold uppercase tracking-tighter">{{ auth()->user()->role }}</span>
                        </div>
                        
                        {{-- Professional Avatar Circle --}}
                        <a href="{{ route('profile.edit') }}" class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 font-black shadow-inner">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 hover:text-indigo-300 transition" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>