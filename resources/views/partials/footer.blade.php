<footer class="bg-gray-900 text-gray-300 py-12 mt-16 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            <div class="space-y-4">
                <h3 class="text-2xl font-bold text-white tracking-tight">
                    PageTurner<span class="text-indigo-500">.</span>
                </h3>
                <p class="text-sm leading-relaxed">
                    Your destination for quality books and literary adventures. Curating stories that stay with you long after the final page.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="hover:text-white transition-colors"><span class="sr-only">Facebook</span><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><span class="sr-only">Instagram</span><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition-colors"><span class="sr-only">Twitter</span><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-6">Explore</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition-colors">Home</a></li>
                    <li><a href="{{ route('books.index') }}" class="hover:text-indigo-400 transition-colors">Browse Books</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-indigo-400 transition-colors">Categories</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-6">Customer Care</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <span class="text-gray-500">Email:</span>
                        <a href="mailto:support@pageturner.com" class="hover:text-white transition-colors">support@pageturner.com</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-500">Phone:</span>
                        <a href="tel:+11234567890" class="hover:text-white transition-colors">(123) 456-7890</a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-6">Stay Updated</h3>
                <p class="text-sm mb-4">Get book recommendations and deals delivered to your inbox.</p>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Enter your email" class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 block w-full">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition-all">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <p>&copy; {{ date('Y') }} PageTurner Bookstore. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <a href="#" class="hover:text-white">Terms of Service</a>
                <a href="#" class="hover:text-white">Cookies</a>
            </div>
        </div>
    </div>
</footer>