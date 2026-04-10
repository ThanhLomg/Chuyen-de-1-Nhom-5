@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Danh sách Khóa học</h2>
        <a href="{{ route('courses.create') }}" class="btn btn-success">
            + Thêm Khóa Học Mới
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên khóa học..." class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted">Giá từ</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="VNĐ" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted">Giá đến</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="VNĐ" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp (Draft)</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" width="5%">ID</th>
                            <th width="30%">Tên Khóa Học</th>
                            <th class="text-center">Số Bài</th>
                            <th class="text-center">Học Viên</th>
                            <th>Giá</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="text-center" width="25%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td class="text-center">{{ $course->id }}</td>
                                <td class="fw-bold text-primary">{{ $course->name }}</td>
                                <td class="text-center">{{ $course->lessons->count() }}</td>
                                <td class="text-center">{{ $course->students->count() }}</td>
                                <td class="text-danger fw-bold">{{ number_format($course->price, 0, ',', '.') }}đ</td>
                                <td class="text-center">
                                    @if($course->status == 'published')
                                        <span class="badge bg-success px-2 py-1">Xuất bản</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">Nháp</span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('lessons.index', $course->id) }}" class="btn btn-sm btn-outline-primary">QL Bài học</a>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-info">Sửa</a>
                                        
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn xóa khóa học này không?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Không tìm thấy khóa học nào phù hợp.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
</div>
@endsection