@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Category: {{ $category->name }}</h4>
        <div>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-sm w-50">
            <tr><th>Name</th><td>{{ $category->name }}</td></tr>
            <tr><th>Products in this category</th><td>{{ $category->products->count() }}</td></tr>
            <tr><th>Created</th><td>{{ $category->created_at?->format('Y-m-d H:i') }}</td></tr>
        </table>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
    </div>
</div>
@endsection
