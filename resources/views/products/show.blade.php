@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Product: {{ $product->name }}</h4>
        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <img src="{{ $product->image_url }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
            </div>
            <div class="col-md-8">
                <table class="table table-bordered table-sm">
                    <tr><th>Name</th><td>{{ $product->name }}</td></tr>
                    <tr><th>SKU</th><td>{{ $product->sku ?? '-' }}</td></tr>
                    <tr><th>Category</th><td>{{ $product->category->name ?? '-' }}</td></tr>
                    <tr><th>Size</th><td>{{ $product->size->name ?? '-' }}</td></tr>
                    <tr><th>Color</th><td>{{ $product->color }}</td></tr>
                    <tr><th>Price</th><td>${{ number_format($product->price, 2) }}</td></tr>
                    <tr>
                        <th>Stock</th>
                        <td>
                            {{ $product->stock_quantity }} (min: {{ $product->min_stock }})
                            @if($product->stock_quantity == 0)
                                <span class="badge bg-danger text-white">Out of Stock</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @else
                                <span class="badge bg-success text-white">In Stock</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Details</th><td>{{ $product->details ?? '-' }}</td></tr>
                    <tr><th>Created</th><td>{{ $product->created_at?->format('Y-m-d H:i') }}</td></tr>
                </table>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
    </div>
</div>
@endsection
