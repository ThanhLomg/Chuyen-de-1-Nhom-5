@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <span>Sản phẩm</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold">Danh sách sản phẩm</h2>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow-sm transition-colors">
            + Thêm sản phẩm
        </a>
    </div>

    {{-- Form lọc --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filter-form">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên sản phẩm..."
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Danh mục</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">Tất cả</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hiển thị</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết (≤5)</option>
                        <option value="out_stock" {{ request('status') == 'out_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors">
                        Lọc
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="border border-gray-300 bg-white px-4 py-2 rounded-md hover:bg-gray-50 text-sm">
                        Reset
                    </a>
                    {{-- Nút Sắp hết - sử dụng màu vàng đậm và chữ trắng --}}
                    <button type="button" onclick="filterByStatus('low_stock')" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-md text-sm transition-colors flex items-center gap-1 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Sắp hết
                    </button>
                    {{-- Nút Hết hàng - sử dụng màu đỏ đậm và chữ trắng --}}
                    <button type="button" onclick="filterByStatus('out_stock')" 
                            class="bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-md text-sm transition-colors flex items-center gap-1 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hết hàng
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Bảng danh sách --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-600">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Ảnh</th>
                    <th class="px-6 py-3">Tên sản phẩm</th>
                    <th class="px-6 py-3">Danh mục</th>
                    <th class="px-6 py-3">Giá</th>
                    <th class="px-6 py-3">Tồn kho</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $product->id }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ $product->image_url }}" class="w-10 h-10 object-cover rounded border">
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ $product->category->name ?? '' }}</td>
                    <td class="px-6 py-4">{{ $product->formatted_display_price }}</td>
                    <td class="px-6 py-4">
                        <span class="@if($product->stock == 0) text-red-600 font-medium @elseif($product->stock <= 5) text-yellow-600 font-medium @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">Sửa</a>
                        <button onclick="confirmDelete({{ $product->id }})" class="text-red-600 hover:underline">Xóa</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-gray-500">Chưa có sản phẩm nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t">
        {{ $products->links() }}
    </div>
</div>

{{-- Modal xác nhận xóa --}}
<div x-data="{ open: false, productId: null }" x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
            <div class="px-6 pt-5 pb-4">
                <h3 class="text-lg font-medium text-gray-900">Xác nhận xóa</h3>
                <p class="mt-2 text-sm text-gray-500">Bạn có chắc muốn xóa sản phẩm này?</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end">
                <button @click="open = false" class="bg-white py-2 px-4 border rounded-md text-sm mr-2">Hủy</button>
                <form :action="'/admin/products/' + productId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-sm transition-colors">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.confirmDelete = (id) => {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.open = true;
        modal.productId = id;
    }

    function filterByStatus(status) {
        const form = document.getElementById('filter-form');
        const existingStatus = form.querySelector('input[name="status"]');
        if (existingStatus) existingStatus.remove();

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        form.submit();
    }
</script>
@endsection