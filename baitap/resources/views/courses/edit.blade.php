@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Sửa Khóa Học: {{ $course->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label>Tên khóa học</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $course->name) }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $course->price) }}" required min="0">
        </div>

        <div class="form-group mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Hình ảnh (Để trống nếu không muốn đổi)</label>
            <input type="file" name="image" class="form-control">
            @if($course->image && $course->image !== 'default.jpg')
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $course->image) }}" alt="Current Image" width="100">
                </div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Xuất bản (Published)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật Khóa Học</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection