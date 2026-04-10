<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Yêu cầu 2.5: Dashboard hiển thị thống kê
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $latestProducts = Product::latest()->take(5)->get(); // 5 sản phẩm mới nhất
        
        return view('dashboard', compact('totalProducts', 'totalCategories', 'latestProducts'));
    }

    // Yêu cầu 2.1b & 3: Hiển thị, Tìm kiếm, Sắp xếp, Phân trang
    public function index(Request $request)
    {
        // Dùng with() để tránh lỗi N+1 query khi lấy Category
        $query = Product::with('category'); 

        // 3.1 Tìm kiếm theo tên
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3.2 Sắp xếp theo giá (Tăng/Giảm dần)
        if ($request->has('sort')) {
            $query->orderBy('price', $request->sort);
        } else {
            $query->latest(); // Mặc định mới nhất
        }

        // Phân trang (5 sản phẩm / trang)
        $products = $query->paginate(5); 

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->except('image');

        // 3.3 Upload ảnh lưu vào storage
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        // Trả về kèm thông báo thành công
        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công'); 
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Lưu ảnh mới
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Product $product)
    {
        // Xóa ảnh trong thư mục trước
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công');
    }
}