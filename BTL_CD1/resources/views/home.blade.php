@extends('layouts.app')

@section('title', 'Trang chủ')
@section('content')
<!-- Hero -->
<section class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-20">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">Không gian sống hoàn hảo</h1>
            <p class="text-lg mb-8 text-blue-100">Khám phá bộ sưu tập nội thất cao cấp, thiết kế tinh tế cho ngôi nhà của bạn.</p>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="bg-white text-blue-900 px-6 py-3 rounded-md font-medium hover:bg-gray-100">Mua sắm ngay</a>
                <a href="#" class="border border-white text-white px-6 py-3 rounded-md font-medium hover:bg-white hover:text-blue-900">Danh mục</a>
            </div>
        </div>
        <div class="md:w-1/2 mt-8 md:mt-0">
            <img src="https://via.placeholder.com/600x400" alt="Hero" class="rounded-lg shadow-xl">
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-12 container mx-auto px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Danh mục nổi bật</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="relative rounded-lg overflow-hidden group">
            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <h3 class="text-white font-semibold text-lg">{{ $cat->name }}</h3>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm nổi bật</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="py-12 container mx-auto px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Hàng mới về</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($newArrivals as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-12 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-10">Tại sao chọn FurniShop?</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-4xl mb-3">🚚</div>
                <h3 class="font-semibold text-lg">Miễn phí vận chuyển</h3>
                <p class="text-gray-300 text-sm">Cho đơn hàng từ 500k</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">⭐</div>
                <h3 class="font-semibold text-lg">Chất lượng đảm bảo</h3>
                <p class="text-gray-300 text-sm">Bảo hành 12 tháng</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">🔄</div>
                <h3 class="font-semibold text-lg">Đổi trả dễ dàng</h3>
                <p class="text-gray-300 text-sm">Trong vòng 7 ngày</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">📞</div>
                <h3 class="font-semibold text-lg">Hỗ trợ 24/7</h3>
                <p class="text-gray-300 text-sm">Hotline: 1900 1234</p>
            </div>
        </div>
    </div>
</section>
@endsection