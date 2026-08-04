@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Product List</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Product name">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Size</label>
                    <select name="size_id" class="form-select">
                        <option value="">All</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Color</label>
                    <input type="text" name="color" value="{{ request('color') }}" class="form-control" placeholder="e.g. Red">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min Price ($)</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" min="0" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max Price ($)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" min="0" step="0.01">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-outline-primary" type="submit">Apply Filters</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th width="130">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $stockClass = $product->stock_quantity <= $product->min_stock
                                ? ($product->stock_quantity == 0 ? 'text-danger' : 'text-warning')
                                : 'text-success';
                        @endphp
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ $product->image_url }}" width="60" class="rounded" alt="{{ $product->name }}">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku ?? '-' }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->size->name ?? '-' }}</td>
                            <td>{{ $product->color }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td class="{{ $stockClass }}">{{ $product->stock_quantity }} (min {{ $product->min_stock }})</td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <h5>No products found</h5>
                                <p class="text-muted">Start by adding your first product!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($products->hasPages())
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
