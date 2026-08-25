@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            Product: {{ $product->name }}
        </h4>

        <div>

            <a href="{{ route('stock-movements.create', $product) }}"
               class="btn btn-sm btn-success">
                Adjust Stock
            </a>

            <a href="{{ route('stock-movements.product-history', $product) }}"
               class="btn btn-sm btn-info">
                Stock History
            </a>

            <a href="{{ route('products.edit', $product) }}"
               class="btn btn-sm btn-warning">
                Edit
            </a>

            <form action="{{ route('products.destroy', $product) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-sm btn-danger">
                    Delete
                </button>

            </form>

        </div>

    </div>


    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <img src="{{ $product->image_url }}"
                     class="img-fluid rounded shadow-sm"
                     alt="{{ $product->name }}">

            </div>


            <div class="col-md-8">

                <table class="table table-bordered">

                    <tr>
                        <th>Name</th>
                        <td>{{ $product->name }}</td>
                    </tr>

                    <tr>
                        <th>SKU</th>
                        <td>{{ $product->sku ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Category</th>
                        <td>{{ $product->category->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Size</th>
                        <td>{{ $product->size->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Color</th>
                        <td>{{ $product->color }}</td>
                    </tr>

                    <tr>
                        <th>Price</th>
                        <td>
                            ${{ number_format($product->price, 2) }}
                        </td>
                    </tr>

                    <tr>

                        <th>Stock</th>

                        <td>

                            <strong>
                                {{ $product->stock_quantity }}
                            </strong>

                            <span class="text-muted">
                                (min: {{ $product->min_stock }})
                            </span>

                            @if($product->stock_quantity == 0)

                                <span class="badge bg-danger">
                                    Out of Stock
                                </span>

                            @elseif($product->isLowStock())

                                <span class="badge bg-warning text-dark">
                                    Low Stock
                                </span>

                            @else

                                <span class="badge bg-success">
                                    In Stock
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>Status</th>

                        <td>

                            @if($product->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>
                        <th>Details</th>
                        <td>{{ $product->details ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Created</th>
                        <td>
                            {{ $product->created_at?->format('Y-m-d H:i') }}
                        </td>
                    </tr>

                </table>

                <a href="{{ route('products.index') }}"
                   class="btn btn-secondary">
                    Back to Products
                </a>

            </div>

        </div>


        <hr class="my-4">


        <h5 class="mb-3">
            Recent Stock Movements
        </h5>


        @if($product->stockMovements->isEmpty())

            <div class="alert alert-info">
                No stock movements recorded yet.
            </div>

        @else

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Previous</th>
                            <th>New Stock</th>
                            <th>Reason</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($product->stockMovements->take(5) as $movement)

                            <tr>

                                <td>
                                    {{ $movement->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td>

                                    <span class="badge bg-{{ $movement->type_badge }}">
                                        {{ $movement->type_label }}
                                    </span>

                                </td>

                                <td>

                                    @if($movement->type === 'stock_out')
                                        <span class="text-danger fw-bold">
                                            -{{ $movement->quantity }}
                                        </span>
                                    @elseif($movement->type === 'stock_in')
                                        <span class="text-success fw-bold">
                                            +{{ $movement->quantity }}
                                        </span>
                                    @else
                                        <span class="text-warning fw-bold">
                                            {{ $movement->quantity }}
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $movement->previous_stock }}
                                </td>

                                <td>
                                    {{ $movement->new_stock }}
                                </td>

                                <td>
                                    {{ $movement->reason ?? '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection