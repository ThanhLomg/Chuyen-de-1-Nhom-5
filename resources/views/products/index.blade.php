@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar Bộ lọc --}}
        <aside class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-semibold text-lg mb-4">🔍 Lọc sản phẩm</h3>
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    {{-- Danh mục --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                        <select name="category" class="w-full border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Khoảng giá --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá</label>
                        <div class="flex gap-2 mb-2">
                            <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}"
                                   class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}"
                                   class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <button type="button" onclick="setPriceRange(0, 1000000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded">Dưới 1tr</button>
                            <button type="button" onclick="setPriceRange(1000000, 5000000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded">1tr - 5tr</button>
                            <button type="button" onclick="setPriceRange(5000000, null)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded">Trên 5tr</button>
                        </div>
                    </div>

                    {{-- Màu sắc --}}
                    @if($colors->isNotEmpty())
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Màu sắc</label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chất liệu</label>
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
                        Áp dụng bộ lọc
                    </button>
                    <a href="{{ route('products.index') }}" class="block text-center mt-2 text-sm text-gray-500 hover:text-primary">
                        Xóa bộ lọc
                    </a>
                </form>
            </div>
        </aside>

        {{-- Danh sách sản phẩm --}}
        <div class="lg:w-3/4">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <p class="text-gray-600">
                    Hiển thị <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> - 
                    <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> 
                    trong <span class="font-medium">{{ $products->total() }}</span> sản phẩm
                </p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Sắp xếp:</label>
                    <select name="sort" form="filter-form" class="border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        <option value="">Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">Không tìm thấy sản phẩm phù hợp.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 text-primary hover:underline">Xóa bộ lọc</a>
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

@push('scripts')
<script>
    function setPriceRange(min, max) {
        document.querySelector('input[name="min_price"]').value = min || '';
        document.querySelector('input[name="max_price"]').value = max || '';
        document.getElementById('filter-form').submit();
    }
</script>
@endpush
@endsection