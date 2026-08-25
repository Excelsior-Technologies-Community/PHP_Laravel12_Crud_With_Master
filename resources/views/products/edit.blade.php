@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Edit Product: {{ $product->name }}</h4>
    </div>

    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">
                        Category <span class="text-danger">*</span>
                    </label>

                    <select name="category_id"
                            class="form-select"
                            required>

                        <option value="">Select Category</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">
                        Size <span class="text-danger">*</span>
                    </label>

                    <select name="size_id"
                            class="form-select"
                            required>

                        <option value="">Select Size</option>

                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}"
                                {{ old('size_id', $product->size_id) == $size->id ? 'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                        @endforeach

                    </select>
                </div>


                <div class="col-md-6">
                    <label class="form-label">
                        Product Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $product->name) }}"
                           class="form-control"
                           required>
                </div>


                <div class="col-md-6">
                    <label class="form-label">SKU</label>

                    <input type="text"
                           name="sku"
                           value="{{ old('sku', $product->sku) }}"
                           class="form-control">
                </div>


                <div class="col-md-12">
                    <label class="form-label">Details</label>

                    <textarea name="details"
                              rows="3"
                              class="form-control">{{ old('details', $product->details) }}</textarea>
                </div>


                <div class="col-md-6">

                    <label class="form-label">Current Image</label>

                    <div class="mb-2">
                        <img src="{{ $product->image_url }}"
                             width="120"
                             class="rounded shadow-sm"
                             alt="{{ $product->name }}">
                    </div>

                    <label class="form-label">
                        Change Image
                    </label>

                    <input type="file"
                           name="image"
                           class="form-control">

                    <label class="form-label mt-2">
                        Or replace with Image URL
                    </label>

                    <input type="url"
                           name="image_url"
                           value="{{ old('image_url') }}"
                           class="form-control"
                           placeholder="https://example.com/image.jpg">

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Color <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="color"
                           value="{{ old('color', $product->color) }}"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Price ($) <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="price"
                           value="{{ old('price', $product->price) }}"
                           step="0.01"
                           min="0"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Stock Quantity <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="stock_quantity"
                           value="{{ old('stock_quantity', $product->stock_quantity) }}"
                           min="0"
                           class="form-control"
                           required>

                    <small class="text-muted">
                        For proper stock tracking, use Stock Adjustment.
                    </small>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Min Stock <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="min_stock"
                           value="{{ old('min_stock', $product->min_stock) }}"
                           min="0"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Product Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="1"
                            {{ old('status', $product->status ? '1' : '0') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('status', $product->status ? '1' : '0') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="col-12 text-end">

                    <button type="submit"
                            class="btn btn-primary">
                        Update Product
                    </button>

                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary">
                        Back
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection