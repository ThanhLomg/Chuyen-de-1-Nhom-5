@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary">Trang chủ</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700">{{ $category->name }}</span>
    </nav>

    {{-- Tiêu đề danh mục --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-gray-600 mt-2">{{ $category->description }}</p>
        @endif
    </div>

    {{-- Grid sản phẩm (tái sử dụng layout tương tự products index) --}}
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar Bộ lọc (giống products.index) --}}
        <aside class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-semibold text-lg mb-4">Bộ lọc</h3>
                <form method="GET" action="{{ route('categories.show', $category->slug) }}" id="filter-form">
                    {{-- Khoảng giá --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Khoảng giá</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}"
                                   class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}"
                                   class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    {{-- Màu sắc --}}
                    @if($colors->isNotEmpty())
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Màu sắc</label>
                        <div class="space-y-1 max-h-40 overflow-y-auto">
                            @foreach($colors as $color)
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" name="color[]" value="{{ $color }}"
                                           {{ in_array($color, (array) request('color', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2">{{ $color }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Chất liệu --}}
                    @if($materials->isNotEmpty())
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Chất liệu</label>
                        <div class="space-y-1 max-h-40 overflow-y-auto">
                            @foreach($materials as $material)
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" name="material[]" value="{{ $material }}"
                                           {{ in_array($material, (array) request('material', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2">{{ $material }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Chỉ còn hàng --}}
                    <div class="mb-5">
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2">Chỉ hiện sản phẩm còn hàng</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-medium hover:bg-primary-dark transition-colors">
                        Áp dụng
                    </button>
                    <a href="{{ route('categories.show', $category->slug) }}" class="block text-center mt-2 text-sm text-gray-500 hover:text-primary">
                        Xóa bộ lọc
                    </a>
                </form>
            </div>
        </aside>

        {{-- Danh sách sản phẩm --}}
        <div class="lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    <span class="font-medium">{{ $products->total() }}</span> sản phẩm
                </p>
                <select name="sort" form="filter-form" class="border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    <option value="">Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                </select>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">Không có sản phẩm nào trong danh mục này.</p>
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