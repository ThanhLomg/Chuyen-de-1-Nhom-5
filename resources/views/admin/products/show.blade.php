@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .thumbnail-active {
        border: 2px solid #0058A3;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ 
    mainImage: '{{ $product->image_url }}',
    quantity: 1,
    maxStock: {{ $product->stock }},
    changeMainImage(url) { this.mainImage = url; }
}">
    {{-- Breadcrumb --}}
    <nav class="text-sm mb-4">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary">Trang chủ</a> 
        <span class="text-gray-400">/</span>
        <a href="{{ route('categories.show', $product->category->slug) }}" class="text-gray-500 hover:text-primary">{{ $product->category->name }}</a> 
        <span class="text-gray-400">/</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Ảnh sản phẩm --}}
        <div>
            <div class="aspect-square overflow-hidden rounded-lg border">
                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            
            {{-- Thumbnails --}}
            <div class="flex gap-2 mt-2 overflow-x-auto">
                <img src="{{ $product->image_url }}" 
                     @click="changeMainImage('{{ $product->image_url }}')" 
                     :class="{'thumbnail-active': mainImage === '{{ $product->image_url }}'}"
                     class="w-16 h-16 object-cover rounded cursor-pointer border-2 border-transparent hover:border-primary transition">
                @foreach($product->gallery_urls as $img)
                    <img src="{{ $img }}" 
                         @click="changeMainImage('{{ $img }}')" 
                         :class="{'thumbnail-active': mainImage === '{{ $img }}'}"
                         class="w-16 h-16 object-cover rounded cursor-pointer border-2 border-transparent hover:border-primary transition">
                @endforeach
            </div>
        </div>

        {{-- Thông tin sản phẩm --}}
        <div>
            <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 mb-2">{{ $product->name }}</h1>
            
            {{-- Đánh giá (placeholder) --}}
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <span class="text-sm text-gray-500 ml-2">(0 đánh giá)</span>
            </div>

            {{-- Giá --}}
            <div class="mb-4">
                <span class="text-3xl font-bold text-primary">{{ $product->formatted_display_price }}</span>
                @if($product->sale_price)
                    <span class="text-lg text-gray-400 line-through ml-3">{{ $product->formatted_price }}</span>
                    <span class="ml-3 bg-red-500 text-white text-sm font-bold px-2 py-1 rounded">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            {{-- Trạng thái kho --}}
            <div class="mb-4">
                @if($product->stock > 0)
                    @if($product->stock <= 5)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                            Chỉ còn {{ $product->stock }} sản phẩm
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Còn hàng
                        </span>
                    @endif
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Hết hàng
                    </span>
                @endif
            </div>

            {{-- Form thêm vào giỏ hàng --}}
            @if($product->stock > 0)
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center border rounded-md">
                    <button type="button" @click="if(quantity > 1) quantity--" class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
                    <input type="number" x-model="quantity" min="1" :max="maxStock" class="w-16 text-center border-x py-2 focus:outline-none" readonly>
                    <button type="button" @click="if(quantity < maxStock) quantity++" class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
                </div>
                <button onclick="addToCart({{ $product->id }}, quantity)" 
                        class="flex-1 bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-md transition">
                    Thêm vào giỏ hàng
                </button>
            </div>
            @else
            <button disabled class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-6 rounded-md cursor-not-allowed mb-6">
                Hết hàng
            </button>
            @endif

            {{-- Bảng thông số --}}
            <div class="border-t pt-4">
                <h3 class="font-semibold text-gray-800 mb-3">Thông số sản phẩm</h3>
                <table class="w-full text-sm">
                    <tbody>
                        @if($product->material)
                        <tr class="border-b">
                            <td class="py-2 text-gray-600 w-1/3">Chất liệu</td>
                            <td class="py-2">{{ $product->material }}</td>
                        </tr>
                        @endif
                        @if($product->dimensions)
                        <tr class="border-b">
                            <td class="py-2 text-gray-600">Kích thước</td>
                            <td class="py-2">{{ $product->dimensions }}</td>
                        </tr>
                        @endif
                        @if($product->color)
                        <tr class="border-b">
                            <td class="py-2 text-gray-600">Màu sắc</td>
                            <td class="py-2">{{ $product->color }}</td>
                        </tr>
                        @endif
                        @if($product->brand)
                        <tr class="border-b">
                            <td class="py-2 text-gray-600">Thương hiệu</td>
                            <td class="py-2">{{ $product->brand }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Mô tả ngắn --}}
            @if($product->short_description)
            <div class="mt-4">
                <p class="text-gray-700">{{ $product->short_description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Mô tả chi tiết --}}
    <div class="mt-12" x-data="{ open: true }">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center cursor-pointer" @click="open = !open">
            Mô tả sản phẩm
            <svg class="ml-2 w-5 h-5 transform transition-transform" :class="{'rotate-180': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </h3>
        <div x-show="open" class="prose max-w-none text-gray-700">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    {{-- Sản phẩm liên quan --}}
    @if($related->count() > 0)
    <div class="mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Sản phẩm liên quan</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($related as $relProduct)
                <x-product-card :product="$relProduct" />
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection