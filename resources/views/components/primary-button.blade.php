<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-indigo-200'
]) }}>
    {{ $slot }}
</button>