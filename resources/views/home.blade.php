@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<section class="relative bg-gray-900 text-white h-[80vh]">
    <div class="swiper myHeroSlider w-full h-full">
        <div class="swiper-wrapper">
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/sofa_da.webp') }}');">
                <div class="absolute inset-0 bg-black/50"></div>                
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Sofa Da Cao Cấp</h1>
                    <p class="text-lg mb-8 text-gray-200">Đẳng cấp Ý, nâng tầm phòng khách nhà bạn.</p>
                    <a href="{{ route('products.index') }}" class="bg-white text-gray-900 px-8 py-3 rounded-md font-bold hover:bg-gray-200 transition">Mua sắm ngay</a>
                </div>
            </div>
            
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('/img/ban_an_go_soi.jpg');">                
                <div class="absolute inset-0 bg-black/50"></div> 
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Bộ Bàn Ăn Gỗ Sồi</h1>
                    <p class="text-lg mb-8 text-gray-200">Bữa cơm gia đình thêm phần ấm cúng.</p>
                    <a href="{{ route('products.index') }}" class="bg-white text-gray-900 px-8 py-3 rounded-md font-bold hover:bg-gray-200 transition">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</section>

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

<section class="py-12 container mx-auto px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Hàng mới về</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($newArrivals as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof Swiper !== 'undefined') {
            new Swiper('.myHeroSlider', {
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            });
        }
    });
</script>
@endsection