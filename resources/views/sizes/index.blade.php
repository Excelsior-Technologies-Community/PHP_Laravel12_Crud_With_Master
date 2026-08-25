@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">
        Size List
    </h4>

    <a href="{{ route('sizes.create') }}"
        class="btn btn-primary">

        + Add Size

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
            action="{{ route('sizes.index') }}">

            <div class="row g-2">

                <div class="col-md-10">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search size name or code...">

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

                        <th>Code</th>

                        <th>Description</th>

                        <th width="180">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($sizes as $size)

                    <tr>

                        <td>
                            {{ $size->id }}
                        </td>

                        <td>
                            {{ $size->name }}
                        </td>

                        <td>

                            {{ $size->code ?? '-' }}

                        </td>

                        <td>

                            {{ $size->description ?? '-' }}

                        </td>

                        <td>

                            <a href="{{ route('sizes.show', $size) }}"
                                class="btn btn-sm btn-info">

                                View

                            </a>


                            <a href="{{ route('sizes.edit', $size) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>


                            <form action="{{ route('sizes.destroy', $size) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete this size?')">

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

                        <td colspan="5"
                            class="text-center py-3">

                            No sizes found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection