@extends('layouts.master')

@section('content')
<h2 class="mt-4">Thêm Sản Phẩm Mới</h2>
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3"><label>Tên sản phẩm</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label>Giá</label><input type="number" name="price" class="form-control" required></div>
    <div class="mb-3"><label>Số lượng</label><input type="number" name="quantity" class="form-control" required></div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label>Ảnh sản phẩm</label><input type="file" name="image" class="form-control"></div>
    <button type="submit" class="btn btn-success">Lưu lại</button>
</form>
@endsection