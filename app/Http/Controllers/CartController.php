<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }
        
        if ($product->track_quantity && $product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        $cart = $this->getCart();
        
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }
        
        return back()->with('success', 'Product added to cart!');
    }
    
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        if ($cartItem->product->track_quantity && $cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return back()->with('success', 'Cart updated!');
    }
    
    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Item removed from cart!');
    }
    
    private function getCart()
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        
        return $cart->load('items.product');
    }
}
