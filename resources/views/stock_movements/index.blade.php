@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h4>
        Stock Movement History
    </h4>

    <a href="{{ route('products.index') }}"
        class="btn btn-secondary">
        Back to Products
    </a>

</div>


<div class="card mb-3">

    <div class="card-body">

        <form method="GET"
            action="{{ route('stock-movements.index') }}">

            <div class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label class="form-label">
                        Search Product
                    </label>

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Product name or SKU">

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Movement Type
                    </label>

                    <select name="type"
                        class="form-select">

                        <option value="">
                            All Types
                        </option>

                        <option value="stock_in"
                            {{ request('type') === 'stock_in' ? 'selected' : '' }}>
                            Stock In
                        </option>

                        <option value="stock_out"
                            {{ request('type') === 'stock_out' ? 'selected' : '' }}>
                            Stock Out
                        </option>

                        <option value="adjustment"
                            {{ request('type') === 'adjustment' ? 'selected' : '' }}>
                            Adjustment
                        </option>

                    </select>

                </div>


                <div class="col-md-3">

                    <button type="submit"
                        class="btn btn-outline-primary">
                        Apply
                    </button>

                    <a href="{{ route('stock-movements.index') }}"
                        class="btn btn-outline-secondary">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Date</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Previous</th>
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

                            <a href="{{ route('products.show', $movement->product) }}">

                                {{ $movement->product->name }}

                            </a>

                        </td>

                        <td>
                            {{ $movement->product->sku ?? '-' }}
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

                        <td colspan="8"
                            class="text-center py-4">

                            No stock movements found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            @if($movements->lastPage() > 1)
            <div class="mt-3 d-flex justify-content-center">
                <nav aria-label="Stock movement pagination">
                    <ul class="pagination mb-0">

                        @for($page = 1; $page <= $movements->lastPage(); $page++)

                            @if($page == $movements->currentPage())
                            <li class="page-item active">
                                <span class="page-link">
                                    {{ $page }}
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $movements->appends(request()->query())->url($page) }}">
                                    {{ $page }}
                                </a>
                            </li>
                            @endif

                            @endfor

                    </ul>
                </nav>
            </div>
            @endif

        </div>

    </div>

</div>

@endsection