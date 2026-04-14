@extends('layouts.app')

@section('title', 'Sản phẩm')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar filters -->
        <aside class="md:w-1/4">
            <div class="bg-white p-4 rounded-lg shadow-sm sticky top-20">
                <h3 class="font-semibold text-lg mb-4">Bộ lọc</h3>
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <!-- Từ khóa tìm kiếm (giữ lại nếu có) -->
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Danh mục</label>
                        <select name="category" class="w-full border-gray-300 rounded-md">
                            <option value="">Tất cả</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Khoảng giá -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Giá (VNĐ)</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}" class="w-1/2 border-gray-300 rounded-md">
                            <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}" class="w-1/2 border-gray-300 rounded-md">
                        </div>
                    </div>

                    <!-- Màu sắc -->
                    @if($colors->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Màu sắc</label>
                        @foreach($colors as $color)
                            <label class="flex items-center text-sm">
                                <input type="checkbox" name="color" value="{{ $color }}" {{ request('color') == $color ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                                <span class="ml-2">{{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                    <!-- Chất liệu -->
                    @if($materials->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Chất liệu</label>
                        @foreach($materials as $material)
                            <label class="flex items-center text-sm">
                                <input type="checkbox" name="material" value="{{ $material }}" {{ request('material') == $material ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                                <span class="ml-2">{{ $material }}</span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                    <!-- Còn hàng -->
                    <div class="mb-4">
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                            <span class="ml-2">Còn hàng</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary-dark">Áp dụng</button>
                </form>
            </div>
        </aside>

        <!-- Product grid -->
        <div class="md:w-3/4">
            <div class="flex justify-between items-center mb-4">
                <p class="text-gray-600">
                    Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} trong {{ $products->total() }} sản phẩm
                </p>
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm text-gray-600">Sắp xếp:</label>
                    <select name="sort" id="sort" form="filter-form" class="border-gray-300 rounded-md text-sm">
                        <option value="">Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Không tìm thấy sản phẩm nào.</p>
                    <a href="{{ route('products.index') }}" class="text-primary hover:underline">Xóa bộ lọc</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection