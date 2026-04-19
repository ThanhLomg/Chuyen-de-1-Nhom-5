@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary">Sản phẩm</a> /
    <span>{{ $product->name }}</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
          x-data="productEditForm({{ json_encode($product->gallery_urls ?? []) }}, {{ json_encode($product->gallery ?? []) }})">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Cột trái: Thông tin cơ bản --}}
            <div class="space-y-6">
                {{-- Tên sản phẩm --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Tên sản phẩm <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Danh mục --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Danh mục <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả ngắn --}}
                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">
                        Mô tả ngắn
                    </label>
                    <textarea name="short_description" id="short_description" rows="2"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('short_description', $product->short_description) }}</textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả chi tiết --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Mô tả chi tiết <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giá & Giá khuyến mãi --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Giá gốc (VNĐ) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="1000" min="1000"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">
                            Giá khuyến mãi (VNĐ)
                        </label>
                        <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="1000" min="1000"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tồn kho & Chất liệu --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                            Tồn kho <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-1">
                            Chất liệu
                        </label>
                        <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- Kích thước & Màu sắc --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">
                            Kích thước
                        </label>
                        <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $product->dimensions) }}"
                               placeholder="VD: 120x60x75 cm"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                            Màu sắc
                        </label>
                        <input type="text" name="color" id="color" value="{{ old('color', $product->color) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- Thương hiệu --}}
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">
                        Thương hiệu
                    </label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Checkbox: Nổi bật & Hiển thị --}}
                <div class="flex items-center gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Sản phẩm nổi bật</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Hiển thị</span>
                    </label>
                </div>
            </div>

            {{-- Cột phải: Ảnh --}}
            <div class="space-y-6">
                {{-- Ảnh chính hiện tại --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh chính hiện tại</label>
                    <img src="{{ $product->image_url }}" class="h-40 w-40 object-cover rounded border mb-2">
                </div>

                {{-- Upload ảnh chính mới --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Thay đổi ảnh chính
                    </label>
                    <input type="file" name="image" accept="image/*" @change="previewMainImage"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3">
                        <img x-ref="mainImagePreview" src="#" alt="Preview" class="h-40 w-40 object-cover rounded border hidden">
                    </div>
                </div>

                {{-- Gallery hiện tại --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Thư viện ảnh hiện tại
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($product->gallery_urls as $index => $url)
                            <div class="relative">
                                <img src="{{ $url }}" class="h-24 w-full object-cover rounded border">
                                <label class="absolute -top-2 -right-2 cursor-pointer">
                                    <input type="checkbox" name="remove_gallery[]" value="{{ $product->gallery[$index] }}" class="sr-only peer">
                                    <span class="bg-white border rounded-full w-5 h-5 flex items-center justify-center text-red-500 peer-checked:bg-red-500 peer-checked:text-white transition-colors">
                                        &times;
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Upload thêm ảnh gallery --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Thêm ảnh mới (tối đa 6 ảnh tổng cộng)
                    </label>
                    <input type="file" name="gallery[]" accept="image/*" multiple @change="addGalleryImages"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('gallery.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <template x-for="(url, index) in galleryPreviews" :key="'new-'+index">
                            <div class="relative">
                                <img :src="url" class="h-24 w-full object-cover rounded border">
                                <button type="button" @click="removeGalleryImage(index)"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Tổng số ảnh: {{ count($product->gallery_urls) }} hiện tại + <span x-text="galleryPreviews.length"></span> mới
                    </p>
                </div>
            </div>
        </div>

        {{-- Nút submit - ĐÃ SỬA MÀU --}}
        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm font-medium transition-colors">
                Cập nhật sản phẩm
            </button>
        </div>
    </form>
</div>

<script>
    function productEditForm(existingGalleryUrls, existingGalleryPaths) {
        return {
            galleryPreviews: [],
            previewMainImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.$refs.mainImagePreview.src = URL.createObjectURL(file);
                    this.$refs.mainImagePreview.classList.remove('hidden');
                } else {
                    this.$refs.mainImagePreview.src = '#';
                    this.$refs.mainImagePreview.classList.add('hidden');
                }
            },
            addGalleryImages(event) {
                const files = Array.from(event.target.files);
                const currentCount = existingGalleryUrls.length + this.galleryPreviews.length;
                const remaining = 6 - currentCount;
                const filesToAdd = files.slice(0, remaining);
                
                filesToAdd.forEach(file => {
                    this.galleryPreviews.push(URL.createObjectURL(file));
                });

                if (files.length > remaining) {
                    alert(`Tổng số ảnh gallery tối đa là 6. Đã bỏ qua ${files.length - remaining} ảnh.`);
                }
            },
            removeGalleryImage(index) {
                this.galleryPreviews.splice(index, 1);
            }
        }
    }
</script>
@endsection