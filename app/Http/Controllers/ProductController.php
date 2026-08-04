<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'size'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when($request->input('category_id'), function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->input('size_id'), function ($query, $sizeId) {
                $query->where('size_id', $sizeId);
            })
            ->when($request->input('color'), function ($query, $color) {
                $query->where('color', $color);
            })
            ->when($request->input('min_price'), function ($query, $minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($request->input('max_price'), function ($query, $maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sizes'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();

        return view('products.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $image = $this->resolveImage($request);

        if ($image === null) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'Please provide either an image file or an image URL.']);
        }

        Product::create([
            'category_id' => $request->category_id,
            'size_id' => $request->size_id,
            'name' => $request->name,
            'details' => $request->details,
            'image' => $image,
            'color' => $request->color,
            'price' => $request->price,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,'.$product->id,
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $image = $this->resolveImage($request, $product);

        if ($image !== null) {
            $product->image = $image;
        }

        $product->update([
            'category_id' => $request->category_id,
            'size_id' => $request->size_id,
            'name' => $request->name,
            'details' => $request->details,
            'color' => $request->color,
            'price' => $request->price,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    protected function uploadImage(Request $request): string
    {
        $imageName = time().'_'.uniqid().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        return $imageName;
    }

    protected function deleteImage(?string $imageName): void
    {
        if ($imageName && File::exists(public_path('images/'.$imageName))) {
            File::delete(public_path('images/'.$imageName));
        }
    }

    /**
     * Resolve the image value from either an uploaded file or an online
     * image URL. Returns null when neither is provided so the caller can
     * decide how to handle the missing input.
     */
    protected function resolveImage(Request $request, ?Product $product = null): ?string
    {
        if ($request->hasFile('image')) {
            if ($product) {
                $this->deleteImage($product->image);
            }

            return $this->uploadImage($request);
        }

        if ($request->filled('image_url')) {
            if ($product) {
                $this->deleteImage($product->image);
            }

            return $request->input('image_url');
        }

        return null;
    }
}
