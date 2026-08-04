@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Size: {{ $size->name }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('sizes.update', $size) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Size Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $size->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" value="{{ old('code', $size->code) }}" class="form-control @error('code') is-invalid @enderror" placeholder="e.g. S, M, L">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Optional">{{ old('description', $size->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Update Size</button>
            <a href="{{ route('sizes.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
