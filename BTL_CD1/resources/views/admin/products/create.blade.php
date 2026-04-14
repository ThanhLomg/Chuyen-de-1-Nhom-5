@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary">Sản phẩm</a> /
    <span>Thêm mới</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" x-data="productForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md @error('category_id') border-red-500 @enderror">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mô tả ngắn</label>
                    <textarea name="short_description" rows="2" class="w-full border-gray-300 rounded-md">{{ old('short_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mô tả chi tiết <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" class="w-full border-gray-300 rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" step="1000" min="1000" class="w-full border-gray-300 rounded-md @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" step="1000" min="1000" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Chất liệu</label>
                        <input type="text" name="material" value="{{ old('material') }}" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Kích thước</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions') }}" placeholder="VD: 120x60x75 cm" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Màu sắc</label>
                        <input type="text" name="color" value="{{ old('color') }}" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Thương hiệu</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" class="w-full border-gray-300 rounded-md">
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                        <span class="ml-2">Sản phẩm nổi bật</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                        <span class="ml-2">Hiển thị</span>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Ảnh chính <span class="text-red-500">*</span></label>
                    <input type="file" name="image" accept="image/*" @change="previewMainImage" class="w-full border-gray-300 rounded-md @error('image') border-red-500 @enderror">
                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <div class="mt-2">
                        <img x-ref="mainImagePreview" src="#" alt="Preview" class="h-40 w-40 object-cover rounded border hidden">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Thư viện ảnh (tối đa 6 ảnh)</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple @change="addGalleryImages" class="w-full border-gray-300 rounded-md">
                    @error('gallery.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <template x-for="(url, index) in galleryPreviews" :key="index">
                            <div class="relative">
                                <img :src="url" class="h-24 w-full object-cover rounded border">
                                <button type="button" @click="removeGalleryImage(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">&times;</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md shadow-sm hover:bg-primary-dark">Thêm sản phẩm</button>
        </div>
    </form>
</div>

<script>
    function productForm() {
        return {
            galleryPreviews: [],
            previewMainImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.$refs.mainImagePreview.src = URL.createObjectURL(file);
                    this.$refs.mainImagePreview.classList.remove('hidden');
                }
            },
            addGalleryImages(event) {
                const files = Array.from(event.target.files);
                files.forEach(file => {
                    this.galleryPreviews.push(URL.createObjectURL(file));
                });
            },
            removeGalleryImage(index) {
                this.galleryPreviews.splice(index, 1);
            }
        }
    }
</script>
@endsection