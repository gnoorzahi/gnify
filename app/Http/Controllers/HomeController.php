<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();
        
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
}
