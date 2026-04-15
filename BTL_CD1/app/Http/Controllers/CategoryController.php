<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm theo danh mục
     */
    public function show(string $slug): View
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::active()->withCount('products')->get();
        $colors = Product::active()->distinct()->pluck('color')->filter()->sort()->values();
        $materials = Product::active()->distinct()->pluck('material')->filter()->sort()->values();

        return view('categories.show', compact('category', 'products', 'categories', 'colors', 'materials'));
    }
}