@extends('layouts.shop')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col">
                <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-300">
                            <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <div class="flex items-center">
                        <p class="text-3xl text-gray-900">${{ number_format($product->price, 2) }}</p>
                        @if($product->compare_price)
                            <p class="ml-4 text-xl text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500">Category:</span>
                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-500">SKU:</span>
                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $product->sku }}</span>
                    </div>
                    @if($product->track_quantity)
                        <div class="flex items-center mt-2">
                            <span class="text-sm text-gray-500">Stock:</span>
                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $product->stock_quantity }} available</span>
                        </div>
                    @endif
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="quantity" class="sr-only">Quantity</label>
                            <select name="quantity" id="quantity" class="max-w-full rounded-md border border-gray-300 py-1.5 px-3 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @for($i = 1; $i <= min(10, $product->stock_quantity); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <button type="submit" 
                                class="flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-300 disabled:cursor-not-allowed"
                                @if($product->track_quantity && $product->stock_quantity <= 0) disabled @endif>
                            @if($product->track_quantity && $product->stock_quantity <= 0)
                                Out of Stock
                            @else
                                Add to Cart
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Related products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-24">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Related Products</h2>
                <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                    <img src="{{ $relatedProduct->images[0] }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection