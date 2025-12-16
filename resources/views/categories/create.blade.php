@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Add Category</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <button class="btn btn-success">Save</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
