@extends('layouts.master')

@section('content')
    <h2 class="mt-4">Danh Sách Sản Phẩm</h2>
    
    <x-alert :message="session('success')" />

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Thêm Sản Phẩm</a>
        
        <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Tìm tên..." value="{{ request('search') }}">
            <select name="sort" class="form-select">
                <option value="">Sắp xếp</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>
            <button type="submit" class="btn btn-secondary">Lọc</button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" style="object-fit: cover">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td> 
                <td>{{ number_format($product->price) }} VNĐ</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Chắc chắn xóa?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
@endsection