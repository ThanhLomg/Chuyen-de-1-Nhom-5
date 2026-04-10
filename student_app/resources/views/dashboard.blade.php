@extends('layouts.master')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Bảng Điều Khiển (Dashboard)</h2>
    <p class="text-muted">Tổng quan về hệ thống quản lý khóa học của bạn.</p>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title">Tổng Khóa Học</h5>
                <h2 class="mb-0 fw-bold">{{ $totalCourses }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title">Đang Xuất Bản</h5>
                <h2 class="mb-0 fw-bold">{{ $publishedCourses }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-dark bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title">Bản Nháp</h5>
                <h2 class="mb-0 fw-bold">{{ $draftCourses }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h5 class="card-title">Giá Trung Bình</h5>
                <h3 class="mb-0 fw-bold">{{ number_format($averagePrice) }} đ</h3>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <a href="{{ route('courses.index') }}" class="btn btn-dark btn-lg shadow-sm">
        Quản Lý Danh Sách Khóa Học Ngay &rarr;
    </a>
</div>
@endsection