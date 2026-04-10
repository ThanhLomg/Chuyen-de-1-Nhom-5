@extends('layouts.master')

@section('content')
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Thêm Khóa Học Mới</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf <div class="mb-3">
                <label class="form-label fw-bold">Tên khóa học <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nhập tên khóa học...">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Ví dụ: 500000">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả khóa học...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <button type="submit" class="btn btn-success">Lưu Khóa Học</button>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Hủy & Quay lại</a>
        </form>
    </div>
</div>
@endsection