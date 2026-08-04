@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Size Master</h4>
    <a href="{{ route('sizes.create') }}" class="btn btn-primary">+ Add Size</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sizes as $size)
                        <tr>
                            <td>{{ $size->id }}</td>
                            <td>{{ $size->name }}</td>
                            <td>{{ $size->code ?? '-' }}</td>
                            <td>{{ $size->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('sizes.show', $size) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('sizes.edit', $size) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('sizes.destroy', $size) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this size?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <h5>No sizes found</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
