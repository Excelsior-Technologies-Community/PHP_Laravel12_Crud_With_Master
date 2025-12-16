@extends('layouts.app')

@section('content')
{{-- Main card container for edit form --}}
<div class="card">
    <div class="card-header">
        <h4>Edit Product: {{ $product->name }}</h4>
        {{-- Display product name in header for context --}}
    </div>

    <div class="card-body">
        {{-- Edit form with multipart encoding for file uploads --}}
        <form action="{{ route('products.update', $product->id) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf {{-- Laravel CSRF protection token --}}
            @method('PUT') {{-- Spoof PUT method for updates --}}

            {{-- Display validation errors if any --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Product Name Field --}}
            <div class="mb-3">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $product->name) }}" 
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Enter product name"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Product Details Field --}}
            <div class="mb-3">
                <label class="form-label">Details <span class="text-danger">*</span></label>
                <textarea name="details" 
                          class="form-control @error('details') is-invalid @enderror"
                          rows="4"
                          placeholder="Enter product details"
                          required>{{ old('details', $product->details) }}</textarea>
                @error('details')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Current Image Preview --}}
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="{{ asset('images/' . $product->image) }}" 
                     width="120" 
                     height="120"
                     class="rounded mb-2 shadow-sm"
                     alt="{{ $product->name }}">
            </div>

            {{-- New Image Upload (Optional) --}}
            <div class="mb-3">
                <label class="form-label">Change Image (Leave empty to keep current)</label>
                <input type="file" 
                       name="image" 
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Product Size Field --}}
            <div class="mb-3">
                <label class="form-label">Size <span class="text-danger">*</span></label>
                <input type="text" 
                       name="size" 
                       value="{{ old('size', $product->size) }}" 
                       class="form-control @error('size') is-invalid @enderror"
                       placeholder="e.g., Small, Medium, Large, 38, M"
                       required>
                @error('size')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Product Color Field --}}
            <div class="mb-3">
                <label class="form-label">Color <span class="text-danger">*</span></label>
                <input type="text" 
                       name="color" 
                       value="{{ old('color', $product->color) }}" 
                       class="form-control @error('color') is-invalid @enderror"
                       placeholder="e.g., Red, Blue, Black"
                       required>
                @error('color')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Category Dropdown --}}
            <div class="mb-3">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="categories" 
                        class="form-control @error('categories') is-invalid @enderror" 
                        required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->name }}" 
                                {{ old('categories', $product->categories) == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('categories')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Price Field --}}
            <div class="mb-3">
                <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                <input type="number" 
                       name="price" 
                       value="{{ old('price', $product->price) }}" 
                       step="0.01"
                       min="0"
                       class="form-control @error('price') is-invalid @enderror"
                       placeholder="0.00"
                       required>
                @error('price')
                    <div class="invalid-feedback">{{ $error }}</div>
                @enderror
            </div>

            {{-- Form Action Buttons --}}
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
