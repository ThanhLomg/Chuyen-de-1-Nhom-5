@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Thống Kê</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng Khóa Học</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;">{{ $totalCourses }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng Học Viên</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng Doanh Thu</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">Khóa Học Nhiều Học Viên Nhất</div>
                <div class="card-body">
                    @if($topCourse)
                        <h5>{{ $topCourse->name }}</h5>
                        <p>Giá: <strong>{{ number_format($topCourse->price, 0, ',', '.') }}đ</strong></p>
                        <p>Số lượng đăng ký: <span class="badge bg-success">{{ $topCourse->students_count }} học viên</span></p>
                    @else
                        <p class="text-muted">Chưa có dữ liệu đăng ký.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">5 Khóa Học Mới Nhất</div>
                <ul class="list-group list-group-flush">
                    @forelse($recentCourses as $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $course->name }}
                            <span class="badge bg-primary rounded-pill">{{ $course->status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Chưa có khóa học nào.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection