<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm với bộ lọc và tìm kiếm.
     */
    public function index(Request $request): View
    {
        $query = Product::active()->with('category');

        // 1. Tìm kiếm theo từ khóa
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($q2) => $q2->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->orWhere('short_description', 'like', "%{$q}%"));
        }

        // 2. Lọc theo danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        /**
         * 3. Lọc theo khoảng giá (ĐÃ FIX)
         * Sử dụng COALESCE để lấy sale_price nếu có, nếu không lấy price
         */
        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$request->min_price]);
        }
        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$request->max_price]);
        }

        // 4. Lọc theo màu sắc (mảng)
        if ($request->filled('color')) {
            $colors = (array) $request->color;
            $query->whereIn('color', $colors);
        }

        // 5. Lọc theo chất liệu (mảng)
        if ($request->filled('material')) {
            $materials = (array) $request->material;
            $query->whereIn('material', $materials);
        }

        // 6. Chỉ còn hàng
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        /**
         * 7. Sắp xếp (ĐÃ FIX)
         * Ưu tiên giá sau giảm khi sắp xếp giá tăng/giảm dần
         */
        $query->when($request->sort, function($q, $sort) {
            return match($sort) {
                'price_asc'  => $q->orderByRaw('COALESCE(sale_price, price) ASC'),
                'price_desc' => $q->orderByRaw('COALESCE(sale_price, price) DESC'),
                'name_asc'   => $q->orderBy('name', 'ASC'),
                'popular'    => $q->orderByDesc('views'),
                default      => $q->latest(),
            };
        }, fn($q) => $q->latest());

        // Lấy dữ liệu cho sidebar và phân trang
        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('products')->get();
        
        // Lấy danh sách màu và chất liệu để hiển thị bộ lọc
        $colors     = Product::active()->distinct()->pluck('color')->filter()->sort()->values();
        $materials  = Product::active()->distinct()->pluck('material')->filter()->sort()->values();

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
        $related = Product::active()
            ->where('stock', '>', 0)
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
        
        $results = Product::active()
            ->where('stock', '>', 0)
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