@extends('layouts.admin')

@section('title', 'Quản lý danh mục')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <span>Danh mục</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold">Danh sách danh mục</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow-sm transition-colors">
            + Thêm danh mục
        </a>
    </div>

    {{-- Bảng danh sách --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-600">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Ảnh</th>
                    <th class="px-6 py-3">Tên</th>
                    <th class="px-6 py-3">Danh mục cha</th>
                    <th class="px-6 py-3">Số sản phẩm</th>
                    <th class="px-6 py-3">Thứ tự</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $category->id }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ $category->image_url }}" class="w-10 h-10 object-cover rounded border">
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4">{{ $category->parent ? $category->parent->name : '-' }}</td>
                    <td class="px-6 py-4">{{ $category->products_count ?? $category->products()->count() }}</td>
                    <td class="px-6 py-4">{{ $category->sort_order }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">Sửa</a>
                        
                        {{-- SỬA: Dùng onclick để gọi hàm JS cho chắc chắn --}}
                        <button type="button" 
                                onclick="confirmDelete({{ $category->id }})" 
                                class="text-red-600 hover:underline">
                            Xóa
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-gray-500">Chưa có danh mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t">
        {{ $categories->links() }}
    </div>
</div>

{{-- Modal Xóa --}}
<div x-data="{ open: false, categoryId: null }" 
     x-show="open" 
     @open-delete-modal.window="open = true; categoryId = $event.detail.id"
     @keydown.escape.window="open = false" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4">
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full z-50" x-show="open">
            <div class="px-6 pt-5 pb-4">
                <h3 class="text-lg font-medium text-gray-900">Xác nhận xóa</h3>
                <p class="mt-2 text-sm text-gray-500">Bạn có chắc muốn xóa danh mục này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end">
                <button @click="open = false" type="button" class="bg-white py-2 px-4 border rounded-md text-sm mr-2 hover:bg-gray-100">Hủy</button>
                
                {{-- Form xóa --}}
                <form :action="'{{ url('admin/categories') }}/' + categoryId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-sm transition-colors">
                        Xóa vĩnh viễn
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm này sẽ phát tín hiệu cho AlpineJS ở Modal nhận
    function confirmDelete(id) {
        window.dispatchEvent(new CustomEvent('open-delete-modal', { 
            detail: { id: id } 
        }));
    }
</script>
@endsection