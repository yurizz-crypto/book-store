<footer class="bg-[#001BB7] text-[#F5F1DC]/80 py-12 mt-16 border-t border-[#0046FF]/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            {{-- Brand Section --}}
            <div class="space-y-4">
                <h3 class="text-2xl font-black text-[#F5F1DC] tracking-tighter uppercase">
                    PageTurner<span class="text-[#FF8040]">.</span>
                </h3>
                <p class="text-sm leading-relaxed">
                    Your destination for quality books and literary adventures. Curating stories that stay with you long after the final page.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="hover:text-[#FF8040] transition-colors"><span class="sr-only">Facebook</span><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-[#FF8040] transition-colors"><span class="sr-only">Instagram</span><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-[#FF8040] transition-colors"><span class="sr-only">Twitter</span><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            {{-- Links Section --}}
            <div>
                <h3 class="text-sm font-black text-[#F5F1DC] uppercase tracking-widest mb-6">Explore</h3>
                <ul class="space-y-3 text-sm font-bold">
                    <li><a href="{{ route('home') }}" class="hover:text-[#FF8040] transition-colors">Home</a></li>
                    <li><a href="{{ route('books.index') }}" class="hover:text-[#FF8040] transition-colors">Browse Books</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-[#FF8040] transition-colors">Categories</a></li>
                </ul>
            </div>

            {{-- Support Section --}}
            <div>
                <h3 class="text-sm font-black text-[#F5F1DC] uppercase tracking-widest mb-6">Customer Care</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex flex-col gap-1">
                        <span class="text-[#F5F1DC]/50 uppercase text-[10px] font-black tracking-widest">Email</span>
                        <a href="mailto:support@pageturner.com" class="hover:text-[#FF8040] transition-colors font-bold">support@pageturner.com</a>
                    </li>
                    <li class="flex flex-col gap-1">
                        <span class="text-[#F5F1DC]/50 uppercase text-[10px] font-black tracking-widest">Phone</span>
                        <a href="tel:+11234567890" class="hover:text-[#FF8040] transition-colors font-bold">(123) 456-7890</a>
                    </li>
                </ul>
            </div>

            {{-- Newsletter Section --}}
            <div>
                <h3 class="text-sm font-black text-[#F5F1DC] uppercase tracking-widest mb-6">Stay Updated</h3>
                <p class="text-sm mb-4">Get book recommendations and deals delivered to your inbox.</p>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Enter your email" 
                           class="bg-[#0046FF]/20 border border-[#0046FF] text-[#F5F1DC] text-sm rounded-xl p-2.5 focus:ring-[#FF8040] focus:border-[#FF8040] block w-full placeholder-[#F5F1DC]/40">
                    <button type="submit" 
                            class="bg-[#FF8040] hover:bg-[#ff9663] text-white font-black uppercase tracking-widest rounded-xl text-sm px-4 py-2.5 transition-all shadow-lg active:scale-95">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-[#0046FF]/30 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-bold uppercase tracking-widest">
            <p class="opacity-60">&copy; {{ date('Y') }} PageTurner Bookstore. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-[#FF8040] transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-[#FF8040] transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-[#FF8040] transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>