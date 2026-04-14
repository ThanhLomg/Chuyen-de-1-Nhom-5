@props(['product'])
<div class="group relative bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow duration-300">
    @if($product->discount_percent)
    <span class="absolute top-2 left-2 z-10 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
        -{{ $product->discount_percent }}%
    </span>
    @endif
    <a href="{{ route('products.show', $product->slug) }}" class="block aspect-[4/3] overflow-hidden">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </a>
    <div class="p-4">
        <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? '' }}</p>
        <a href="{{ route('products.show', $product->slug) }}"
           class="font-medium text-gray-800 hover:text-blue-600 line-clamp-2 mb-2">{{ $product->name }}</a>
        <div class="flex items-center gap-2 mb-3">
            <span class="text-blue-600 font-bold">{{ $product->formatted_display_price }}</span>
            @if($product->sale_price)
            <span class="text-gray-400 text-sm line-through">{{ $product->formatted_price }}</span>
            @endif
        </div>
        @if($product->stock > 0)
        <button onclick="addToCart({{ $product->id }})"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded transition-colors">
            Thêm vào giỏ
        </button>
        @else
        <button disabled class="w-full bg-gray-200 text-gray-500 text-sm font-medium py-2 rounded cursor-not-allowed">
            Hết hàng
        </button>
        @endif
    </div>
</div>