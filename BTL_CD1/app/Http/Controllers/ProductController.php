<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm với bộ lọc và tìm kiếm.
     */
    public function index(Request $request): View
    {
        $query = Product::active()->with('category');

        // Tìm kiếm theo từ khóa
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            });
        }

        // Lọc theo danh mục (slug)
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Lọc theo màu sắc, chất liệu
        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        // Chỉ hiển thị còn hàng
        if ($request->filled('in_stock')) {
            $query->inStock();
        }

        // Sắp xếp
        $sort = $request->input('sort');
        $query->when($sort, function ($q, $sort) {
            return match ($sort) {
                'price_asc'  => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'name_asc'   => $q->orderBy('name'),
                'popular'    => $q->orderByDesc('views'),
                default      => $q->latest(),
            };
        }, function ($q) {
            return $q->latest();
        });

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('products')->get();
        $colors = Product::active()->distinct()->pluck('color')->filter()->sort()->values();
        $materials = Product::active()->distinct()->pluck('material')->filter()->sort()->values();

        return view('products.index', compact('products', 'categories', 'colors', 'materials'));
    }

    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function show(string $slug): View
    {
        $product = Product::active()->where('slug', $slug)->with('category')->firstOrFail();
        
        // Tăng lượt xem
        $product->increment('views');
        
        // Sản phẩm liên quan (cùng danh mục, loại trừ sản phẩm hiện tại, còn hàng)
        $related = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'related'));
    }

    /**
     * API tìm kiếm sản phẩm cho autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }
        
        $results = Product::active()->inStock()
            ->where('name', 'like', '%' . $q . '%')
            ->select('id', 'name', 'slug', 'image', 'price', 'sale_price')
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'name'  => $product->name,
                    'url'   => route('products.show', $product->slug),
                    'image' => $product->image_url,
                    'price' => $product->formatted_display_price,
                ];
            });
            
        return response()->json($results);
    }
}