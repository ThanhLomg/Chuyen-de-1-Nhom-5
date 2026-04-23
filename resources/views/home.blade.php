@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<section class="relative bg-gray-900 text-white h-[80vh]">
    <div class="swiper myHeroSlider w-full h-full">
        <div class="swiper-wrapper">
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/sofa_da.webp') }}');">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Các dòng sản phẩm Sofa Cao Cấp</h1>
                    <p class="text-lg mb-8 text-gray-200">Đẳng cấp Ý, nâng tầm phòng khách nhà bạn.</p>
                </div>
            </div>

            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/ban_an_go_soi.jpg') }}');">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Nghệ thuật từ tay Nghệ nhân</h1>
                    <p class="text-lg mb-8 text-gray-200">Từ những đôi tay tài hoa, tạo nên những tác phẩm nội thất độc đáo và tinh tế.</p>
                </div>
            </div>
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/san_vuon.jpg') }}');">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Nội thất sân vườn cao cấp</h1>
                    <p class="text-lg mb-8 text-gray-200">Đắm mình vào thiên nhiên cùng các nghệ thuật sân vườn cao cấp.</p>
                </div>
            </div>
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/phong_lam_vc.jpg') }}');">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Nâng tầm giá trị không gian làm việc</h1>
                    <p class="text-lg mb-8 text-gray-200">Sở hữu ngay không gian làm việc chuyên nghiệp và thoải mái.</p>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</section>

{{-- Sản phẩm nổi bật --}}
<section class="py-12 container mx-auto px-4">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sản phẩm nổi bật</h2>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors hidden sm:inline-block">Xem tất cả &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden relative flex flex-col">
            <a href="{{ route('products.show', $product->slug) }}" class="relative overflow-hidden aspect-[4/3] bg-gray-100 block">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                {{-- Nhãn giảm giá % (Tự động tính nếu có giá khuyến mãi) --}}
                @if($product->sale_price > 0 && $product->sale_price < $product->price)
                    @php
                        $discount = $product->discount_percent ?: round((($product->price - $product->sale_price) / $product->price) * 100);
                    @endphp
                    <div class="absolute top-3 left-3 z-20">
                        <span style="background-color: #ef4444; color: white;" class="text-xs font-bold px-2.5 py-1 rounded-sm shadow-lg">
                            -{{ $discount }}%
                        </span>
                    </div>
                @endif
            </a>

            <div class="p-5 flex flex-col flex-grow">
                <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'Nội thất' }}</div>
                <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600 transition-colors line-clamp-2">
                    {{ $product->name }}
                </a>

                <div class="mt-auto flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-red-600 font-bold text-lg">{{ $product->formatted_display_price }}</span>
                        @if($product->sale_price)
                            <span class="text-gray-400 text-sm line-through">{{ $product->formatted_price }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Sản phẩm mới --}}
<section class="py-12 container mx-auto px-4 bg-gray-50">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sản phẩm mới</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($newArrivals as $product)
        <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden relative flex flex-col">
            <a href="{{ route('products.show', $product->slug) }}" class="relative overflow-hidden aspect-[4/3]">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                
                {{-- Nhãn giảm giá % (Tự động tính nếu có giá khuyến mãi) --}}
                @if($product->sale_price > 0 && $product->sale_price < $product->price)
                    @php
                        $discount = $product->discount_percent ?: round((($product->price - $product->sale_price) / $product->price) * 100);
                    @endphp
                    <div class="absolute top-3 left-3 z-20">
                        <span style="background-color: #ef4444; color: white;" class="text-xs font-bold px-2.5 py-1 rounded-sm shadow-lg">
                            -{{ $discount }}%
                        </span>
                    </div>
                @endif
            </a>
            <div class="p-5 flex flex-col flex-grow">
                <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'Nội thất' }}</div>
                <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600 transition-colors line-clamp-2">
                    {{ $product->name }}
                </a>
                <div class="mt-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-bold text-lg">{{ $product->formatted_display_price }}</span>
                        @if($product->sale_price)
                            <span class="text-gray-400 text-sm line-through">{{ $product->formatted_price }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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