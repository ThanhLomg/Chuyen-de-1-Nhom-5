@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Thêm Khóa Học Mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <label>Tên khóa học</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
        </div>

        <div class="form-group mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Hình ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Xuất bản (Published)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Khóa Học</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection