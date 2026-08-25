@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">
        Product List
    </h4>

    <div class="d-flex gap-2">

        <a href="{{ route('products.export', request()->query()) }}"
            class="btn btn-success">
            📥 Export CSV
        </a>

        <a href="{{ route('products.create') }}"
            class="btn btn-primary">
            + Add Product
        </a>

    </div>

</div>


<div class="card mb-3">

    <div class="card-body">

        <form method="GET"
            action="{{ route('products.index') }}">

            <div class="row g-3 align-items-end">

                <div class="col-md-3">

                    <label class="form-label">
                        Search
                    </label>

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Product name or SKU">

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Category
                    </label>

                    <select name="category_id"
                        class="form-select">

                        <option value="">
                            All
                        </option>

                        @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Size
                    </label>

                    <select name="size_id"
                        class="form-select">

                        <option value="">
                            All
                        </option>

                        @foreach($sizes as $size)

                        <option value="{{ $size->id }}"
                            {{ request('size_id') == $size->id ? 'selected' : '' }}>

                            {{ $size->name }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                        class="form-select">

                        <option value="">
                            All
                        </option>

                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="col-md-1">

                    <label class="form-label">
                        Color
                    </label>

                    <input type="text"
                        name="color"
                        value="{{ request('color') }}"
                        class="form-control"
                        placeholder="Red">

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Min Price
                    </label>

                    <input type="number"
                        name="min_price"
                        value="{{ request('min_price') }}"
                        class="form-control"
                        min="0"
                        step="0.01">

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Max Price
                    </label>

                    <input type="number"
                        name="max_price"
                        value="{{ request('max_price') }}"
                        class="form-control"
                        min="0"
                        step="0.01">

                </div>

            </div>


            <div class="mt-3">

                <button class="btn btn-outline-primary"
                    type="submit">

                    Apply Filters

                </button>


                <a href="{{ route('products.index') }}"
                    class="btn btn-outline-secondary">

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>


<form method="POST"
    action="{{ route('products.bulk-delete') }}"
    id="bulkDeleteForm">

    @csrf

    @method('DELETE')


    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <button type="submit"
                        id="bulkDeleteButton"
                        class="btn btn-danger"
                        disabled>

                        🗑 Delete Selected

                    </button>

                    <span id="selectedCount"
                        class="text-muted ms-2">

                        0 selected

                    </span>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th width="40">

                                <input type="checkbox"
                                    id="selectAll">

                            </th>

                            <th>#</th>

                            <th>Image</th>

                            <th>Name</th>

                            <th>SKU</th>

                            <th>Category</th>

                            <th>Size</th>

                            <th>Color</th>

                            <th>Price</th>

                            <th>Stock</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                        @php

                        if ($product->stock_quantity == 0) {

                        $stockClass =
                        'text-danger fw-bold';

                        } elseif (
                        $product->stock_quantity
                        <= $product->min_stock
                            ) {

                            $stockClass =
                            'text-warning fw-bold';

                            } else {

                            $stockClass =
                            'text-success fw-bold';

                            }

                            @endphp


                            <tr>

                                <td>

                                    <input type="checkbox"
                                        name="ids[]"
                                        value="{{ $product->id }}"
                                        class="product-checkbox">

                                </td>


                                <td>
                                    {{ $product->id }}
                                </td>


                                <td>

                                    <img src="{{ $product->image_url }}"
                                        width="60"
                                        height="60"
                                        class="rounded object-fit-cover"
                                        alt="{{ $product->name }}">

                                </td>


                                <td>
                                    {{ $product->name }}
                                </td>


                                <td>
                                    {{ $product->sku ?? '-' }}
                                </td>


                                <td>
                                    {{ $product->category->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $product->size->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $product->color }}
                                </td>


                                <td>
                                    ${{ number_format($product->price, 2) }}
                                </td>


                                <td class="{{ $stockClass }}">

                                    {{ $product->stock_quantity }}

                                    <small class="text-muted">

                                        /
                                        min {{ $product->min_stock }}

                                    </small>

                                </td>


                                <td>

                                    <form method="POST"
                                        action="{{ route('products.toggle-status', $product) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="btn btn-sm
                                            {{ $product->status
                                                ? 'btn-success'
                                                : 'btn-secondary' }}">

                                            {{ $product->status
                                            ? 'Active'
                                            : 'Inactive' }}

                                        </button>

                                    </form>

                                </td>


                                <td>

                                    <div class="d-flex flex-wrap gap-1">

                                        <a href="{{ route('products.show', $product) }}"
                                            class="btn btn-sm btn-info">

                                            View

                                        </a>


                                        <a href="{{ route('products.edit', $product) }}"
                                            class="btn btn-sm btn-warning">

                                            Edit

                                        </a>


                                        <a href="{{ route('stock-movements.create', $product) }}"
                                            class="btn btn-sm btn-success">

                                            Stock

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

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="12"
                                    class="text-center py-4">

                                    <h5>
                                        No products found
                                    </h5>

                                    <p class="text-muted">
                                        Start by adding your first product!
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                    </tbody>

                </table>


            @if($products->lastPage() > 1)
    <div class="mt-3 d-flex justify-content-center">
        <nav aria-label="Product pagination">
            <ul class="pagination mb-0">

                @for($page = 1; $page <= $products->lastPage(); $page++)

                    @if($page == $products->currentPage())
                        <li class="page-item active">
                            <span class="page-link">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ $products->appends(request()->query())->url($page) }}">
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

</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectAll =
            document.getElementById('selectAll');

        const checkboxes =
            document.querySelectorAll('.product-checkbox');

        const deleteButton =
            document.getElementById('bulkDeleteButton');

        const selectedCount =
            document.getElementById('selectedCount');

        function updateSelectedCount() {

            const selected =
                document.querySelectorAll(
                    '.product-checkbox:checked'
                ).length;

            selectedCount.textContent =
                selected + ' selected';

            deleteButton.disabled =
                selected === 0;
        }


        selectAll.addEventListener(
            'change',
            function() {

                checkboxes.forEach(function(checkbox) {

                    checkbox.checked =
                        selectAll.checked;

                });

                updateSelectedCount();

            }
        );


        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener(
                'change',
                updateSelectedCount
            );

        });


        document
            .getElementById('bulkDeleteForm')
            .addEventListener('submit', function(event) {

                const selected =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    ).length;

                if (selected === 0) {

                    event.preventDefault();

                    return;

                }

                const confirmed =
                    confirm(
                        'Are you sure you want to delete ' +
                        selected +
                        ' selected product(s)?'
                    );

                if (!confirmed) {

                    event.preventDefault();

                }

            });

    });
</script>

@endsection