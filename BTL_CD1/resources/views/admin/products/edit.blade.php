@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary">Sản phẩm</a> /
    <span>{{ $product->name }}</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="productEditForm({{ json_encode($product->gallery ?? []) }})">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md @error('category_id') border-red-500 @enderror">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mô tả ngắn</label>
                    <textarea name="short_description" rows="2" class="w-full border-gray-300 rounded-md">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mô tả chi tiết <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" class="w-full border-gray-300 rounded-md @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="1000" min="1000" class="w-full border-gray-300 rounded-md @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="1000" min="1000" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Chất liệu</label>
                        <input type="text" name="material" value="{{ old('material', $product->material) }}" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Kích thước</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Màu sắc</label>
                        <input type="text" name="color" value="{{ old('color', $product->color) }}" class="w-full border-gray-300 rounded-md">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Thương hiệu</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="w-full border-gray-300 rounded-md">
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                        <span class="ml-2">Sản phẩm nổi bật</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                        <span class="ml-2">Hiển thị</span>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Ảnh chính</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-40 w-40 object-cover rounded border">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" @change="previewMainImage" class="w-full border-gray-300 rounded-md">
                    <div class="mt-2">
                        <img x-ref="mainImagePreview" src="#" alt="Preview" class="h-40 w-40 object-cover rounded border hidden">
                    </div>
                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Thư viện ảnh (tối đa 6 ảnh)</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple @change="addGalleryImages" class="w-full border-gray-300 rounded-md">
                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <!-- Ảnh hiện có -->
                        <template x-for="(image, index) in existingGallery" :key="'existing-' + index">
                            <div class="relative">
                                <img :src="image.url" class="h-24 w-full object-cover rounded border">
                                <input type="hidden" name="remove_gallery[]" x-model="image.path" x-show="image.removed">
                                <button type="button" @click="toggleRemoveExisting(index)" class="absolute -top-2 -right-2 rounded-full w-5 h-5 flex items-center justify-center text-xs" :class="image.removed ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                                    <span x-show="!image.removed">&times;</span>
                                    <span x-show="image.removed">↻</span>
                                </button>
                            </div>
                        </template>
                        <!-- Ảnh mới thêm -->
                        <template x-for="(url, index) in newGalleryPreviews" :key="'new-' + index">
                            <div class="relative">
                                <img :src="url" class="h-24 w-full object-cover rounded border">
                                <button type="button" @click="removeNewGalleryImage(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">&times;</button>
                            </div>
                        </template>
                    </div>
                    @error('gallery.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md shadow-sm hover:bg-primary-dark">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>

<script>
    function productEditForm(existingGalleryPaths) {
        return {
            existingGallery: existingGalleryPaths.map(path => ({
                path: path,
                url: '{{ asset("storage") }}/' + path,
                removed: false
            })),
            newGalleryPreviews: [],
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
                    this.newGalleryPreviews.push(URL.createObjectURL(file));
                });
            },
            removeNewGalleryImage(index) {
                this.newGalleryPreviews.splice(index, 1);
            },
            toggleRemoveExisting(index) {
                this.existingGallery[index].removed = !this.existingGallery[index].removed;
            }
        }
    }
</script>
@endsection