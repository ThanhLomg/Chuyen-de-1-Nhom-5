@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Quản lý Bài Học - Khóa: <span class="text-primary">{{ $course->name }}</span></h2>
    <a href="{{ route('courses.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách khóa học</a>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Thêm Bài Học Mới</div>
                <div class="card-body">
                    <form action="{{ route('lessons.store', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Tiêu đề</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nội dung</label>
                            <textarea name="content" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Video URL (nếu có)</label>
                            <input type="url" name="video_url" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Thứ tự</label>
                            <input type="number" name="order" class="form-control" value="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Lưu Bài Học</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Tiêu đề</th>
                        <th>Video</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                        <tr>
                            <td>{{ $lesson->order }}</td>
                            <td>{{ $lesson->title }}</td>
                            <td>
                                @if($lesson->video_url)
                                    <a href="{{ $lesson->video_url }}" target="_blank">Xem Video</a>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" onsubmit="return confirm('Xóa bài học này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có bài học nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection