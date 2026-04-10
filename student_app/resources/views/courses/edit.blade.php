@extends('layouts.master')

@section('content')
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Cập Nhật Khóa Học: {{ $course->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="mb-3">
                <label class="form-label fw-bold">Tên khóa học <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $course->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $course->price) }}">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Xuất bản</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện hiện tại</label>
                <br>    
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" width="150" class="img-thumbnail mb-2">
                @else
                    <span class="text-muted d-block mb-2">Khóa học này chưa có ảnh.</span>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-primary">Chỉ chọn file ảnh mới nếu em muốn thay thế ảnh cũ.</small>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <button type="submit" class="btn btn-warning fw-bold">Cập Nhật</button>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Hủy & Quay lại</a>
        </form>
    </div>
</div>
@endsection