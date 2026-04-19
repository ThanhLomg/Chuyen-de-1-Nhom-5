@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ 
    mainImage: '{{ $product->image_url }}', 
    quantity: 1,
    maxStock: {{ $product->stock }},
    addToCart() {
        if (this.quantity < 1) this.quantity = 1;
        if (this.quantity > this.maxStock) this.quantity = this.maxStock;
        window.addToCart({{ $product->id }}, this.quantity);
    }
}">
    {{-- Breadcrumb --}}
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary">Trang chủ</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('categories.show', $product->category->slug) }}" class="text-gray-500 hover:text-primary">{{ $product->category->name }}</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {{-- Cột trái: Hình ảnh --}}
        <div>
            {{-- Ảnh chính --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-4">
                <img :src="mainImage" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
            </div>

            {{-- Thumbnail gallery --}}
            @if($product->gallery_urls && count($product->gallery_urls) > 0)
                <div class="flex gap-2 overflow-x-auto pb-2">
                    {{-- Ảnh chính --}}
                    <button @click="mainImage = '{{ $product->image_url }}'" class="flex-shrink-0 w-20 h-20 border-2 rounded-lg overflow-hidden hover:border-primary transition-colors" 
                            :class="mainImage === '{{ $product->image_url }}' ? 'border-primary' : 'border-transparent'">
                        <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                    </button>
                    {{-- Các ảnh gallery --}}
                    @foreach($product->gallery_urls as $url)
                        <button @click="mainImage = '{{ $url }}'" class="flex-shrink-0 w-20 h-20 border-2 rounded-lg overflow-hidden hover:border-primary transition-colors"
                                :class="mainImage === '{{ $url }}' ? 'border-primary' : 'border-transparent'">
                            <img src="{{ $url }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Cột phải: Thông tin sản phẩm --}}
        <div>
            {{-- Danh mục --}}
            <a href="{{ route('categories.show', $product->category->slug) }}" class="text-sm text-primary hover:underline">
                {{ $product->category->name }}
            </a>

            {{-- Tên sản phẩm --}}
            <h1 class="text-3xl font-bold text-gray-900 mt-2 mb-4">{{ $product->name }}</h1>

            {{-- Giá --}}
            <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl font-bold text-primary">{{ $product->formatted_display_price }}</span>
                @if($product->sale_price)
                    <span class="text-lg text-gray-400 line-through">{{ $product->formatted_price }}</span>
                    <span class="bg-red-100 text-red-700 text-sm font-medium px-2 py-1 rounded">
                        -{{ $product->discount_percent }}%
                    </span>
                @endif
            </div>

            {{-- Trạng thái tồn kho --}}
            <div class="mb-6">
                @if($product->stock > 0)
                    <span class="inline-flex items-center gap-1 text-green-700 bg-green-50 px-3 py-1 rounded-full text-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        Còn {{ $product->stock }} sản phẩm
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 px-3 py-1 rounded-full text-sm">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        Hết hàng
                    </span>
                @endif
            </div>

            {{-- Mô tả ngắn --}}
            @if($product->short_description)
                <div class="text-gray-600 mb-6">
                    {{ $product->short_description }}
                </div>
            @endif

            {{-- Form thêm vào giỏ hàng --}}
            @if($product->stock > 0)
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex items-center border border-gray-200 rounded-lg">
                        <button @click="if(quantity > 1) quantity--" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100">−</button>
                        <input type="number" x-model="quantity" min="1" :max="maxStock" class="w-16 h-10 border-x border-gray-200 text-center focus:outline-none">
                        <button @click="if(quantity < maxStock) quantity++" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100">+</button>
                    </div>
                    <button @click="addToCart" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                        Thêm vào giỏ hàng
                    </button>
                </div>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-500 font-medium py-3 px-6 rounded-lg cursor-not-allowed mb-8">
                    Hết hàng
                </button>
            @endif

            {{-- Thông tin chi tiết --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-semibold text-gray-900 mb-3">Thông tin chi tiết</h3>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    @if($product->material)
                        <dt class="text-gray-500">Chất liệu</dt>
                        <dd class="text-gray-900">{{ $product->material }}</dd>
                    @endif
                    @if($product->dimensions)
                        <dt class="text-gray-500">Kích thước</dt>
                        <dd class="text-gray-900">{{ $product->dimensions }}</dd>
                    @endif
                    @if($product->color)
                        <dt class="text-gray-500">Màu sắc</dt>
                        <dd class="text-gray-900">{{ $product->color }}</dd>
                    @endif
                    @if($product->brand)
                        <dt class="text-gray-500">Thương hiệu</dt>
                        <dd class="text-gray-900">{{ $product->brand }}</dd>
                    @endif
                </dl>
            </div>

            {{-- Mô tả đầy đủ (toggle) --}}
            @if($product->description)
                <div class="border-t border-gray-100 pt-6 mt-6" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-left font-semibold text-gray-900">
                        <span>Mô tả sản phẩm</span>
                        <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-3 prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Sản phẩm liên quan --}}
    @if($related->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection