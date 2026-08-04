@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="display-5">{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Categories</h5>
                <h2 class="display-5">{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Sizes</h5>
                <h2 class="display-5">{{ $totalSizes }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Low Stock ({{ $lowStockProducts->count() }})</h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->isEmpty())
                    <p class="text-muted mb-0">No low-stock products.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $product->name }}</span>
                                <span class="badge bg-warning text-dark">{{ $product->stock_quantity }} / min {{ $product->min_stock }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Out of Stock ({{ $outOfStockProducts->count() }})</h5>
            </div>
            <div class="card-body">
                @if($outOfStockProducts->isEmpty())
                    <p class="text-muted mb-0">No out-of-stock products.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($outOfStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $product->name }}</span>
                                <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
