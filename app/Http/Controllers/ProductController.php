<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // ✅ Import Category model for category dropdowns
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products
     * Fetches all products from database and passes to index view
     */
    public function index()
    {
        $products = Product::all(); // Retrieve all products from database
        return view('products.index', compact('products')); // Pass products to index view
    }

    /**
     * Show the form for creating a new product
     * Loads all categories for dropdown selection in create form
     */
    public function create()
    {
        $categories = Category::all(); // Fetch all categories for dropdown menu
        return view('products.create', compact('categories')); // Pass categories to create form
    }

    /**
     * Store a newly created product in database
     * Handles form validation, image upload, and saves product data
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'categories' => 'required', // Category selection is mandatory
            'name' => 'required',       // Product name is required
            'details' => 'required',    // Product description is required
            'image' => 'required|image', // Product image is required and must be an image file
            'size' => 'required',       // Product size is required
            'color' => 'required',      // Product color is required
            'price' => 'required|numeric', // Price is required and must be numeric
        ]);

        // Generate unique filename for uploaded image and move to public/images folder
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Create new product record with validated data
        Product::create([
            'categories' => $request->categories, // Store selected category name
            'name' => $request->name,
            'details' => $request->details,
            'image' => $imageName,                // Store uploaded image filename
            'size' => $request->size,
            'color' => $request->color,
            'price' => $request->price,           // Store product price
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product
     * Passes current product data and all categories to edit form
     */
    public function edit(Product $product)
    {
        $categories = Category::all(); // Load all categories for edit dropdown
        return view('products.edit', compact('product', 'categories')); 
        // Pass current product and categories to edit view
    }

    /**
     * Update the specified product in database
     * Handles validation, optional image update, and saves changes
     */
    public function update(Request $request, Product $product)
    {
        // Validate incoming request data (image is optional for updates)
        $request->validate([
            'categories' => 'required',
            'name' => 'required',
            'details' => 'required',
            'size' => 'required',
            'color' => 'required',
            'image' => 'image',           // Image is optional during updates
            'price' => 'required|numeric',
        ]);

        // Handle new image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image (optional - add if needed)
            // if ($product->image && file_exists(public_path('images/' . $product->image))) {
            //     unlink(public_path('images/' . $product->image));
            // }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName; // Update image filename
        }

        // Update product with new data
        $product->update([
            'categories' => $request->categories,
            'name' => $request->name,
            'details' => $request->details,
            'size' => $request->size,
            'color' => $request->color,
            'price' => $request->price,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from database
     * Deletes product record and redirects to products list
     */
    public function destroy(Product $product)
    {
        // Optional: Delete associated image file
        // if ($product->image && file_exists(public_path('images/' . $product->image))) {
        //     unlink(public_path('images/' . $product->image));
        // }
        
        $product->delete(); // Delete product from database
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
