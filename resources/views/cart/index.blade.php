@extends('layouts.shop')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Shopping Cart</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="mt-12">
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @php $total = 0 @endphp
                        @foreach($cart->items as $item)
                            @php $subtotal = $item->quantity * $item->price; $total += $subtotal; @endphp
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            @if($item->product->images && count($item->product->images) > 0)
                                                <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="h-16 w-16 rounded-md object-center object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded-md bg-gray-300 flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                            <div class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <!-- Quantity Update -->
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $item->id }}" class="sr-only">Quantity</label>
                                            <select name="quantity" id="quantity-{{ $item->id }}" 
                                                    onchange="this.form.submit()"
                                                    class="rounded-md border border-gray-300 py-1.5 px-3 text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                @for($i = 1; $i <= min(10, $item->product->stock_quantity); $i++)
                                                    <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </form>

                                        <!-- Subtotal -->
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($subtotal, 2) }}
                                        </div>

                                        <!-- Remove Button -->
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Cart Summary -->
                <div class="mt-8 bg-gray-50 rounded-lg px-6 py-6 sm:p-8">
                    <div class="flow-root">
                        <dl class="-my-4 text-sm divide-y divide-gray-200">
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">Subtotal</dt>
                                <dd class="font-medium text-gray-900">${{ number_format($total, 2) }}</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">Shipping</dt>
                                <dd class="font-medium text-gray-900">Calculated at checkout</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">Order total</dt>
                                <dd class="text-base font-medium text-gray-900">${{ number_format($total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4">
                    <a href="{{ route('products.index') }}" class="w-full sm:w-auto bg-white border border-gray-300 rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Continue Shopping
                    </a>
                    @auth
                        <a href="{{ route('checkout.index') }}" class="mt-3 sm:mt-0 w-full sm:w-auto bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mt-3 sm:mt-0 w-full sm:w-auto bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Login to Checkout
                        </a>
                    @endauth
                </div>
            </div>
        @else
            <div class="mt-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-6 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-2 text-gray-500">Start shopping to add items to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Browse Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection