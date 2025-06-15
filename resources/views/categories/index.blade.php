@extends('layouts.shop')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900">Shop by Category</h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Browse our product categories to find exactly what you're looking for
            </p>
        </div>

        @if($categories->count() > 0)
            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($categories as $category)
                    <div class="group relative bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow">
                        <div class="aspect-w-3 aspect-h-2 bg-gray-200 rounded-t-lg overflow-hidden">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-48 object-center object-cover group-hover:opacity-75">
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-300">
                                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                <a href="{{ route('categories.show', $category->slug) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $category->name }}
                                </a>
                            </h3>
                            @if($category->description)
                                <p class="mt-2 text-sm text-gray-500">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                            
                            @if($category->children->count() > 0)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Subcategories:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($category->children->take(3) as $child)
                                            <a href="{{ route('categories.show', $child->slug) }}" 
                                               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                        @if($category->children->count() > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                +{{ $category->children->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <h3 class="mt-6 text-lg font-medium text-gray-900">No categories available</h3>
                <p class="mt-2 text-gray-500">Categories will appear here once they are added to the store.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Browse All Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection