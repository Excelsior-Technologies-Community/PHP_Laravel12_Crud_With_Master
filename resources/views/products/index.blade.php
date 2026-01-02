@extends('layouts.app')

@section('content')
{{-- Main container with page header and add button --}}
<div class="d-flex justify-content-between mb-3">
    <h4>Product List</h4>
    {{-- Link to create new product form --}}
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

{{-- Main card container for products table --}}
<div class="card">
    <div class="card-body">
        {{-- Responsive Bootstrap table with borders and stripes --}}
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    {{-- Table headers for product information --}}
                    <th>Name</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Color</th>
                    
                    <th>Price</th>
                    {{-- Action column with fixed width for buttons --}}
                    <th width="150">Action</th>
                </tr>
            </thead>

            <tbody>
                {{-- Loop through all products passed from controller --}}
                @forelse($products as $product)
                    <tr>
                        {{-- Display product name --}}
                        <td>{{ $product->name }}</td>
                        
                        {{-- Display product image with fixed width and rounded corners --}}
                        <td>
                            <img src="{{ asset('images/'.$product->image) }}" 
                                 width="70" 
                                 class="rounded" 
                                 alt="{{ $product->name }}">
                        </td>
                        
                        <td>{{ $product->size }}</td>
                        <td>{{ $product->color }}</td>
                       
                        
                        {{-- Display price (consider formatting with currency symbol) --}}
                        <td>${{ number_format($product->price, 2) }}</td>
                        
                        <td>
                            {{-- Edit button linking to edit form --}}
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            
                            {{-- Delete form with CSRF protection and method spoofing --}}
                            <form action="{{ route('products.destroy', $product->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?')">
                                @csrf {{-- Laravel CSRF protection token --}}
                                @method('DELETE') {{-- Spoof DELETE HTTP method --}}
                                
                                {{-- Delete button with confirmation dialog --}}
                                <button type="submit"
                                        class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Display message when no products exist --}}
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <h5>No products found</h5>
                            <p class="text-muted">Start by adding your first product!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
