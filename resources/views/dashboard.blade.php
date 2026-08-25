@extends('layouts.app')

@section('content')

<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card text-bg-primary">

            <div class="card-body">

                <h6>
                    Total Products
                </h6>

                <h2 class="display-6">
                    {{ $totalProducts }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card text-bg-success">

            <div class="card-body">

                <h6>
                    Active Products
                </h6>

                <h2 class="display-6">
                    {{ $activeProducts }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card text-bg-secondary">

            <div class="card-body">

                <h6>
                    Inactive Products
                </h6>

                <h2 class="display-6">
                    {{ $inactiveProducts }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3">

        <div class="card text-bg-info">

            <div class="card-body">

                <h6>
                    Total Stock
                </h6>

                <h2 class="display-6">
                    {{ $totalStock }}
                </h2>

            </div>

        </div>

    </div>

</div>


<div class="row g-3 mb-4">

    <div class="col-md-6">

        <div class="card border-success">

            <div class="card-body">

                <h6>
                    Total Categories
                </h6>

                <h2>
                    {{ $totalCategories }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-6">

        <div class="card border-info">

            <div class="card-body">

                <h6>
                    Total Sizes
                </h6>

                <h2>
                    {{ $totalSizes }}
                </h2>

            </div>

        </div>

    </div>

</div>


<div class="row">

    <div class="col-lg-6 mb-4">

        <div class="card border-warning">

            <div class="card-header bg-warning text-dark">

                <h5 class="mb-0">
                    Low Stock ({{ $lowStockProducts->count() }})
                </h5>

            </div>


            <div class="card-body">

                @if($lowStockProducts->isEmpty())

                    <p class="text-muted mb-0">
                        No low-stock products.
                    </p>

                @else

                    <div class="list-group list-group-flush">

                        @foreach($lowStockProducts as $product)

                            <a href="{{ route('products.show', $product) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between">

                                <span>
                                    {{ $product->name }}
                                </span>

                                <span class="badge bg-warning text-dark">

                                    {{ $product->stock_quantity }}

                                    /
                                    min {{ $product->min_stock }}

                                </span>

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>


    <div class="col-lg-6 mb-4">

        <div class="card border-danger">

            <div class="card-header bg-danger text-white">

                <h5 class="mb-0">
                    Out of Stock ({{ $outOfStockProducts->count() }})
                </h5>

            </div>


            <div class="card-body">

                @if($outOfStockProducts->isEmpty())

                    <p class="text-muted mb-0">
                        No out-of-stock products.
                    </p>

                @else

                    <div class="list-group list-group-flush">

                        @foreach($outOfStockProducts as $product)

                            <a href="{{ route('products.show', $product) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between">

                                <span>
                                    {{ $product->name }}
                                </span>

                                <span class="badge bg-danger">
                                    {{ $product->stock_quantity }}
                                </span>

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection