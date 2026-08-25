@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="card">

            <div class="card-header">

                <h4 class="mb-0">
                    Stock Adjustment
                </h4>

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


                <div class="alert alert-info">

                    <div class="row">

                        <div class="col-md-6">

                            <strong>Product:</strong><br>

                            {{ $product->name }}

                        </div>

                        <div class="col-md-6">

                            <strong>Current Stock:</strong><br>

                            <span class="fs-4">
                                {{ $product->stock_quantity }}
                            </span>

                        </div>

                    </div>

                </div>


                <form action="{{ route('stock-movements.store', $product) }}"
                      method="POST">

                    @csrf


                    <div class="mb-3">

                        <label class="form-label">
                            Movement Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="type"
                                id="movementType"
                                class="form-select"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            <option value="stock_in"
                                {{ old('type') === 'stock_in' ? 'selected' : '' }}>
                                Stock In
                            </option>

                            <option value="stock_out"
                                {{ old('type') === 'stock_out' ? 'selected' : '' }}>
                                Stock Out
                            </option>

                            <option value="adjustment"
                                {{ old('type') === 'adjustment' ? 'selected' : '' }}>
                                Set Exact Stock
                            </option>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            <span id="quantityLabel">
                                Quantity
                            </span>

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="quantity"
                               id="quantity"
                               value="{{ old('quantity') }}"
                               min="0"
                               class="form-control"
                               required>

                        <small id="quantityHelp"
                               class="text-muted">

                            Enter the quantity to add or remove.

                        </small>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Reason
                        </label>

                        <input type="text"
                               name="reason"
                               value="{{ old('reason') }}"
                               class="form-control"
                               maxlength="255"
                               placeholder="e.g. New purchase, sale, damaged item">

                    </div>


                    <div class="alert alert-secondary">

                        <strong>How it works:</strong>

                        <ul class="mb-0 mt-2">

                            <li>
                                <strong>Stock In:</strong>
                                Adds quantity to current stock.
                            </li>

                            <li>
                                <strong>Stock Out:</strong>
                                Removes quantity from current stock.
                            </li>

                            <li>
                                <strong>Set Exact Stock:</strong>
                                Replaces the current stock with the entered quantity.
                            </li>

                        </ul>

                    </div>


                    <div class="text-end">

                        <button type="submit"
                                class="btn btn-success">
                            Update Stock
                        </button>

                        <a href="{{ route('products.show', $product) }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

    const movementType = document.getElementById('movementType');
    const quantityLabel = document.getElementById('quantityLabel');
    const quantityHelp = document.getElementById('quantityHelp');

    function updateQuantityLabel() {

        if (movementType.value === 'stock_in') {

            quantityLabel.textContent = 'Quantity to Add';

            quantityHelp.textContent =
                'This quantity will be added to the current stock.';

        } else if (movementType.value === 'stock_out') {

            quantityLabel.textContent = 'Quantity to Remove';

            quantityHelp.textContent =
                'This quantity will be removed from the current stock.';

        } else if (movementType.value === 'adjustment') {

            quantityLabel.textContent = 'New Stock Quantity';

            quantityHelp.textContent =
                'Enter the exact stock quantity that should remain.';

        } else {

            quantityLabel.textContent = 'Quantity';

            quantityHelp.textContent =
                'Enter the quantity to add, remove, or set.';

        }

    }

    movementType.addEventListener(
        'change',
        updateQuantityLabel
    );

    updateQuantityLabel();

</script>

@endsection