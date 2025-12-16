@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Add Product</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Details</label>
                <textarea name="details" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label>Size</label>
                <input type="text" name="size" class="form-control">
            </div>

            <div class="mb-3">
                <label>Color</label>
                <input type="text" name="color" class="form-control">
            </div>
            <div class="mb-3">
    <label>Category</label>
    <select name="categories" class="form-control" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label>Price</label>    
    <input type="text" name="price" class="form-control">
</div>

            <button class="btn btn-success">Save Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
