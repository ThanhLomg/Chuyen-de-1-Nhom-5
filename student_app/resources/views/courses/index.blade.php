@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh Sách Khóa Học</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">Thêm Khóa Học</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('courses.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên khóa học..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort_price" class="form-select">
                    <option value="">-- Sắp xếp giá --</option>
                    <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Lọc & Tìm</button>
            </div>
        </form>
    </div>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên khóa học</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($courses as $course)
        <tr>
            <td>{{ $course->id }}</td>
            <td>
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" width="60" class="img-thumbnail">
                @else
                    <span class="text-muted">Không có ảnh</span>
                @endif
            </td>
            <td>
                <strong>{{ $course->name }}</strong><br>
                <small class="text-muted">Bài học: {{ $course->lessons->count() }} | Học viên: {{ $course->enrollments->count() }}</small>
            </td>
            <td class="text-danger fw-bold">{{ number_format($course->price) }} VNĐ</td>
            <td>
                <x-badge :status="$course->status" />
            </td>
            <td>
                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Chưa có khóa học nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection