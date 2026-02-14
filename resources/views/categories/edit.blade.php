@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-12">
        <div class="bg-white shadow sm:rounded-xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold mb-6">Edit Category</h2>
            <x-category-form 
                :category="$category" 
                :action="route('admin.categories.update', $category)" 
            />
        </div>
    </div>
@endsection