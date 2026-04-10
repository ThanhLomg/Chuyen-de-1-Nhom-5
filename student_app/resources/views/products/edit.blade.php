@extends('layouts.master')

@section('content')
<h2 class="mt-4">Sửa Sản Phẩm</h2>
<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3"><label>Tên sản phẩm</label><input type="text" name="name" value="{{ $product->name }}" class="form-control" required></div>
    <div class="mb-3"><label>Giá</label><input type="number" name="price" value="{{ $product->price }}" class="form-control" required></div>
    <div class="mb-3"><label>Số lượng</label><input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control" required></div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Ảnh sản phẩm</label><br>
        @if($product->image) <img src="{{ asset('storage/' . $product->image) }}" width="100" class="mb-2"> @endif
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-warning">Cập nhật</button>
</form>
@endsection