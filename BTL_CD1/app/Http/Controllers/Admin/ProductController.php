<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm với bộ lọc (bao gồm lọc sắp hết)
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái (bao gồm low_stock)
        if ($request->filled('status')) {
            match ($request->status) {
                'active'    => $query->where('is_active', true),
                'inactive'  => $query->where('is_active', false),
                'low_stock' => $query->where('stock', '<=', 5)->where('stock', '>', 0),
                'out_stock' => $query->where('stock', 0),
                default     => null,
            };
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Form tạo sản phẩm mới
     */
    public function create(): View
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']    = $this->generateUniqueSlug($data['name']);
        $data['image']   = $this->handleImageUpload($request, 'image');
        $data['gallery'] = $this->handleGalleryUpload($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    /**
     * Form chỉnh sửa sản phẩm
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->filled('name') && $request->name !== $product->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        }

        $data['image']   = $this->handleImageUpload($request, 'image', $product->image);
        $data['gallery'] = $this->handleGalleryUpload($request, $product->gallery ?? []);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active');

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Không thể xóa sản phẩm đã có trong đơn hàng.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->gallery ?? [] as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    /**
     * Tạo slug duy nhất
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;
        while (Product::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    /**
     * Xử lý upload ảnh chính
     */
    private function handleImageUpload(Request $request, string $field, ?string $oldPath = null): ?string
    {
        if (!$request->hasFile($field)) {
            return $oldPath;
        }

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $request->file($field)->store('products', 'public');
    }

    /**
     * Xử lý upload gallery
     */
    private function handleGalleryUpload(Request $request, array $existing = []): array
    {
        $gallery = $existing;

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
        }

        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $imgPath) {
                if (Storage::disk('public')->exists($imgPath)) {
                    Storage::disk('public')->delete($imgPath);
                }
                $gallery = array_values(array_diff($gallery, [$imgPath]));
            }
        }

        return array_slice($gallery, 0, 6);
    }
}