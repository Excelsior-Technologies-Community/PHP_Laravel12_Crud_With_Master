@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Size: {{ $size->name }}</h4>
        <div>
            <a href="{{ route('sizes.edit', $size) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('sizes.destroy', $size) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Delete this size?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-sm w-50">
            <tr><th>Name</th><td>{{ $size->name }}</td></tr>
            <tr><th>Code</th><td>{{ $size->code ?? '-' }}</td></tr>
            <tr><th>Description</th><td>{{ $size->description ?? '-' }}</td></tr>
            <tr><th>Products using this size</th><td>{{ $size->products->count() }}</td></tr>
            <tr><th>Created</th><td>{{ $size->created_at?->format('Y-m-d H:i') }}</td></tr>
        </table>
        <a href="{{ route('sizes.index') }}" class="btn btn-secondary">Back to Sizes</a>
    </div>
</div>
@endsection
