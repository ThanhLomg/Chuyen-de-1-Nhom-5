<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::active()->inStock()->featured()
            ->with('category')->latest()->take(8)->get();
        $categories = Category::active()->withCount('products')->take(8)->get();
        $newArrivals = Product::active()->inStock()
            ->with('category')->latest()->take(4)->get();
        return view('home', compact('featuredProducts', 'categories', 'newArrivals'));
    }
}