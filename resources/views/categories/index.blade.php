@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">
        Category List
    </h4>

    <a href="{{ route('categories.create') }}"
        class="btn btn-primary">

        + Add Category

    </a>

</div>


@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif


@if(session('error'))

<div class="alert alert-danger">
    {{ session('error') }}
</div>

@endif


<div class="card mb-3">

    <div class="card-body">

        <form method="GET"
            action="{{ route('categories.index') }}">

            <div class="row g-2">

                <div class="col-md-10">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search category name...">

                </div>

                <div class="col-md-2">

                    <button type="submit"
                        class="btn btn-outline-primary w-100">

                        🔎 Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Products</th>

                        <th width="150">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($categories as $category)

                    <tr>

                        <td>
                            {{ $category->id }}
                        </td>

                        <td>
                            {{ $category->name }}
                        </td>

                        <td>

                            <span class="badge bg-primary">

                                {{ $category->products_count }}

                            </span>

                        </td>

                        <td>

                            <a href="{{ route('categories.show', $category) }}"
                                class="btn btn-sm btn-info">

                                View

                            </a>


                            <a href="{{ route('categories.edit', $category) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>


                            <form action="{{ route('categories.destroy', $category) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete this category?')">

                                @csrf

                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4"
                            class="text-center py-3">

                            No categories found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection