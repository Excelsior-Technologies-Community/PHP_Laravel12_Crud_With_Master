@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <div>

        <h4>
            Stock History
        </h4>

        <p class="text-muted mb-0">
            {{ $product->name }}

            @if($product->sku)
                | SKU: {{ $product->sku }}
            @endif
        </p>

    </div>


    <div>

        <a href="{{ route('stock-movements.create', $product) }}"
           class="btn btn-success">
            Adjust Stock
        </a>

        <a href="{{ route('products.show', $product) }}"
           class="btn btn-secondary">
            Back
        </a>

    </div>

</div>


<div class="row mb-4">

    <div class="col-md-4">

        <div class="card text-bg-primary">

            <div class="card-body">

                <h6>
                    Current Stock
                </h6>

                <h2>
                    {{ $product->stock_quantity }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card text-bg-warning">

            <div class="card-body">

                <h6>
                    Minimum Stock
                </h6>

                <h2>
                    {{ $product->min_stock }}
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card text-bg-success">

            <div class="card-body">

                <h6>
                    Product Status
                </h6>

                <h2>

                    {{ $product->status ? 'Active' : 'Inactive' }}

                </h2>

            </div>

        </div>

    </div>

</div>


<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                    <tr>

                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Previous Stock</th>
                        <th>New Stock</th>
                        <th>Reason</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($movements as $movement)

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

                            @if($movement->type === 'stock_in')

                                <span class="text-success fw-bold">
                                    +{{ $movement->quantity }}
                                </span>

                            @elseif($movement->type === 'stock_out')

                                <span class="text-danger fw-bold">
                                    -{{ $movement->quantity }}
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
                            <strong>
                                {{ $movement->new_stock }}
                            </strong>
                        </td>

                        <td>
                            {{ $movement->reason ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-4">

                            No stock history available.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>


            @if($movements->hasPages())

                {{ $movements->links() }}

            @endif

        </div>

    </div>

</div>

@endsection