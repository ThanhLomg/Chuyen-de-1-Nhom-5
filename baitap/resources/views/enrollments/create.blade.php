@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Đăng ký Khóa học cho Học viên</h2>

    @if(session('danger'))
        <div class="alert alert-danger">{{ session('danger') }}</div>
    @endif

    <form action="{{ route('enrollments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Chọn Khóa Học</label>
            <select name="course_id" class="form-control" required>
                <option value="">-- Chọn khóa học đã xuất bản --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }} ({{ number_format($course->price, 0, ',', '.') }}đ)</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Chọn Học Viên</label>
            <select name="student_id" class="form-control" required>
                <option value="">-- Chọn học viên --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Xác nhận đăng ký</button>
        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection