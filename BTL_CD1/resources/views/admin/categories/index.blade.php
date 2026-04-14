@extends('layouts.admin')

@section('title', 'Quản lý danh mục')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <span>Danh mục</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Danh sách danh mục</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">+ Thêm danh mục</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Ảnh</th>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Danh mục cha</th>
                    <th class="px-4 py-3">Số sản phẩm</th>
                    <th class="px-4 py-3">Thứ tự</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $category->id }}</td>
                    <td class="px-4 py-3">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-10 h-10 object-cover rounded">
                    </td>
                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3">{{ $category->parent ? $category->parent->name : '-' }}</td>
                    <td class="px-4 py-3">{{ $category->products_count ?? $category->products()->count() }}</td>
                    <td class="px-4 py-3">{{ $category->sort_order }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 mr-3">Sửa</a>
                        <button @click="openDeleteModal({{ $category->id }})" class="text-red-600 hover:text-red-800">Xóa</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Chưa có danh mục nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div x-data="{ open: false, categoryId: null }" x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-md w-full">
            <div class="bg-white px-6 pt-5 pb-4">
                <h3 class="text-lg font-medium text-gray-900">Xác nhận xóa danh mục</h3>
                <p class="mt-2 text-sm text-gray-500">Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end">
                <button @click="open = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Hủy</button>
                <form :action="'/admin/categories/' + categoryId" method="POST" class="ml-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('deleteModal', () => ({
            open: false,
            categoryId: null,
            openDeleteModal(id) {
                this.categoryId = id;
                this.open = true;
            }
        }));
    });

    // For simplicity, we can define a global function if not using Alpine component
    window.openDeleteModal = function(id) {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.open = true;
        modal.categoryId = id;
    }
</script>
@endsection