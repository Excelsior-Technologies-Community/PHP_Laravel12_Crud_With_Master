@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Category List</h4>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th width="150">Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('categories.edit',$category->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('categories.destroy',$category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                              onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
