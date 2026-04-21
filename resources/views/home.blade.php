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
                    <a href="{{ route('products.index', ['category' => 'ghe-sofa']) }}" class="bg-white text-gray-900 px-8 py-3 rounded-md font-bold hover:bg-gray-200 transition">Mua sắm ngay</a>
                </div>
            </div>
            
            <div class="swiper-slide relative bg-cover bg-center" style="background-image: url('{{ asset('img/ban_an_go_soi.jpg') }}');">                
                <div class="absolute inset-0 bg-black/50"></div> 
                <div class="relative z-10 flex flex-col justify-center items-start h-full container mx-auto px-4 md:w-1/2">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Bộ Bàn Ăn Gỗ Sồi</h1>
                    <p class="text-lg mb-8 text-gray-200">Bữa cơm gia đình thêm phần ấm cúng.</p>
                    <a href="{{ route('products.index', ['category' => 'ban-an']) }}" class="bg-white text-gray-900 px-8 py-3 rounded-md font-bold hover:bg-gray-200 transition">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</section>

<section class="py-12 container mx-auto px-4">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sản phẩm nổi bật</h2>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors hidden sm:inline-block">Xem tất cả &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden relative flex flex-col">
            {{-- Click vào ảnh để xem chi tiết --}}
            <a href="{{ route('products.show', $product->slug) }}" class="relative overflow-hidden aspect-[4/3] bg-gray-100 block">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                
                <div class="absolute top-3 left-3 flex flex-col gap-2">
                    @if($product->sale_price)
                        <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-sm shadow-sm">SALE</span>
                    @endif
                    <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded-sm shadow-sm">HOT</span>
                </div>

                {{-- Nút Thêm vào giỏ (Form POST) --}}
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none group-hover:pointer-events-auto">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="bg-white text-gray-900 font-bold py-2.5 px-6 rounded-full hover:bg-blue-600 hover:text-white transition-colors transform translate-y-4 group-hover:translate-y-0 duration-300">
                            Thêm vào giỏ
                        </button>
                    </form>
                </div>
            </a>

            <div class="p-5 flex flex-col flex-grow">
                <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'Nội thất' }}</div>
                <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600 transition-colors line-clamp-2">
                    {{ $product->name }}
                </a>
                
                <div class="mt-auto flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-red-600 font-bold text-lg">{{ number_format($product->sale_price ?? $product->price) }}đ</span>
                        @if($product->sale_price)
                            <span class="text-gray-400 text-sm line-through">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="py-12 container mx-auto px-4 bg-gray-50">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sản phẩm mới</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($newArrivals as $product)
            {{-- Sử dụng cấu trúc tương tự ở trên --}}
            <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden relative flex flex-col">
                <a href="{{ route('products.show', $product->slug) }}" class="relative overflow-hidden aspect-[4/3]">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3">
                        <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-sm shadow-sm">HOT</span>
                    </div>
                </a>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                    <span class="text-blue-600 font-bold">{{ number_format($product->price) }}đ</span>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Các section cam kết giữ nguyên --}}

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