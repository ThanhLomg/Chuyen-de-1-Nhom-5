@extends('layout.master')

@section('content')
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Tổng Sản Phẩm</h5>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Tổng Danh Mục</h5>
                <h2>{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>
</div>

<h4 class="mt-4">5 Sản Phẩm Mới Nhất</h4>
<ul class="list-group">
    @foreach($latestProducts as $product)
        <li class="list-group-item">{{ $product->name }} - {{ number_format($product->price) }} VNĐ</li>
    @endforeach
</ul>
@endsection