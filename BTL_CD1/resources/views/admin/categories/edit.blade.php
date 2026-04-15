@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-primary">Danh mục</a> /
    <span>{{ $category->name }}</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            {{-- Tên danh mục --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Tên danh mục <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Danh mục cha --}}
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Danh mục cha</label>
                <select name="parent_id" id="parent_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Không có --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mô tả --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea name="description" id="description" rows="3" 
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
            </div>

            {{-- Ảnh danh mục --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Ảnh danh mục</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ $category->image_url }}" class="h-32 w-32 object-cover rounded border">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="image-preview" src="#" alt="Preview" class="h-32 w-32 object-cover rounded border hidden">
                </div>
            </div>

            {{-- Thứ tự và trạng thái --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" id="sort_order" 
                           value="{{ old('sort_order', $category->sort_order) }}" min="0" 
                           class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Hiển thị</label>
                </div>
            </div>

            {{-- Nút submit - ĐÃ SỬA MÀU --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Cập nhật danh mục
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    }
</script>
@endsection