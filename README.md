# PHP_Laravel12_Crud_With_Master



---

## STEP 1: Install Laravel 12

Create a fresh Laravel 12 project:

```
composer create-project laravel/laravel example-app
```

Explanation:  
This command installs a new Laravel 12 application from scratch.

---

## STEP 2: MySQL Database Configuration

Laravel 12 uses SQLite by default.  
To use MySQL, update your `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Your database name 
DB_USERNAME=root
DB_PASSWORD=root
```

Explanation:  
This connects Laravel to a MySQL database.

---

## STEP 3: Create Products Migration

Create migration:

```
php artisan make:migration create_products_table --create=products
```

### Migration File

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('details');
            $table->decimal('price', 8, 2);
            $table->string('size');
            $table->string('color');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
```

Run migration:

```
php artisan migrate
```

---

## STEP 4: Add Resource Route

`routes/web.php`

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

Explanation:  
Creates all CRUD routes automatically.

---

## STEP 5: Create Controller and Model

```
php artisan make:controller ProductController --resource --model=Product
```

### Product Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'image',
        'size',
        'color',
        'price'
    ];
}
```

---

### Product Controller (WITHOUT Categories)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'image' => 'required|image',
            'size' => 'required',
            'color' => 'required',
            'price' => 'required|numeric',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name' => $request->name,
            'details' => $request->details,
            'image' => $imageName,
            'size' => $request->size,
            'color' => $request->color,
            'price' => $request->price,
        ]);

        return redirect()->route('products.index')->with('success','Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'details' => 'required',
            'size' => 'required',
            'color' => 'required',
            'image' => 'image',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name' => $request->name,
            'details' => $request->details,
            'size' => $request->size,
            'color' => $request->color,
            'price' => $request->price,
        ]);

        return redirect()->route('products.index')->with('success','Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success','Product deleted successfully!');
    }
}
```

---

## STEP 6: Product Blade Files

### Layout File

`resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Product CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('products.index') }}">Product CRUD</a>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
```

---

### `products/index.blade.php`

```blade
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Product List</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Size</th>
            <th>Color</th>
            <th>Price</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td><img src="{{ asset('images/'.$product->image) }}" width="70"></td>
            <td>{{ $product->size }}</td>
            <td>{{ $product->color }}</td>
            <td>${{ number_format($product->price,2) }}</td>
            <td>
                <a href="{{ route('products.edit',$product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
```

---

### `products/create.blade.php`

```blade
@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h4>Add Product</h4></div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" class="form-control mb-2" placeholder="Name">
            <textarea name="details" class="form-control mb-2" placeholder="Details"></textarea>
            <input type="file" name="image" class="form-control mb-2">
            <input type="text" name="size" class="form-control mb-2" placeholder="Size">
            <input type="text" name="color" class="form-control mb-2" placeholder="Color">
            <input type="text" name="price" class="form-control mb-2" placeholder="Price">
            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>

@endsection
```

---

### `products/edit.blade.php`

```blade
@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h4>Edit Product</h4></div>
    <div class="card-body">
        <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ $product->name }}" class="form-control mb-2">
            <textarea name="details" class="form-control mb-2">{{ $product->details }}</textarea>
            <img src="{{ asset('images/'.$product->image) }}" width="100">
            <input type="file" name="image" class="form-control mb-2">
            <input type="text" name="size" value="{{ $product->size }}" class="form-control mb-2">
            <input type="text" name="color" value="{{ $product->color }}" class="form-control mb-2">
            <input type="text" name="price" value="{{ $product->price }}" class="form-control mb-2">
            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

@endsection
```

---


```
php artisan serve
```

Open:

```
http://127.0.0.1:8000/products
```

<img width="628" height="223" alt="image" src="https://github.com/user-attachments/assets/79e99d2b-d789-415b-86bd-15321b98ca59" />

# STEP 7: Create Categories CRUD

Step 7.1: Create Category Model, Migration & Controller
```
php artisan make:model Category -mc
```

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('categories')->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
               $table->dropColumn('categories');
        });
    }
};

```

Run migration:
```
php artisan migrate
```

# Category Model

app/Models/Category.php
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
}
```
# Category Controller

app/Http/Controllers/CategoryController.php
```
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
```
# Categories Routes

routes/web.php
```
use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);
```

# STEP 8: Category Blade Files

# Create folder:

resources/views/categories
```
categories/index.blade.php
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Category List</h4>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
</div>

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

@endsection
```
# categories/create.blade.php
```
@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h4>Add Category</h4></div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <input type="text" name="name" class="form-control mb-2" placeholder="Category Name">
            <button class="btn btn-success">Save</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
```
# categories/edit.blade.php
```
@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h4>Edit Category</h4></div>
    <div class="card-body">
        <form action="{{ route('categories.update',$category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ $category->name }}" class="form-control mb-2">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
```
Run server
```
php artisan serve
```

Open:
```
http://127.0.0.1:8000/categories
```

<img width="1521" height="366" alt="image" src="https://github.com/user-attachments/assets/699c880d-60e1-43f9-8eaa-bd2824a49914" />
<img width="1507" height="422" alt="image" src="https://github.com/user-attachments/assets/c6cb554f-d23f-4b61-ae9e-c57f28c8f3c3" />

# STEP 9: Add Categories Column to Products Table
```
php artisan make:migration add_categories_to_products_table
```
Migration
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('categories')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('categories');
        });
    }
};

```
Run:
```
php artisan migrate
```
STEP 10: Update Product Model

app/Models/Product.php
```
protected $fillable = [
    'name',
    'details',
    'image',
    'size',
    'color',
    'categories',
    'price'
];
```
# STEP 11: Update Product Controller (With Categories)

app/Http/Controllers/ProductController.php
```
use App\Models\Category;
```
Create Method
```
public function create()
{
    $categories = Category::all();
    return view('products.create', compact('categories'));
}

Store Method
'categories' => 'required',

'categories' => $request->categories,

Edit Method
public function edit(Product $product)
{
    $categories = Category::all();
    return view('products.edit', compact('product','categories'));
}

Update Method
'categories' => $request->categories,
```
# STEP 12: Update Product Blade Files (Categories Dropdown)
# products/index.blade.php
```
<th>Category</th>
<td>{{ $product->categories }}</td>
```
# products/create.blade.php
```
<div class="mb-2">
    <label>Category</label>
    <select name="categories" class="form-control" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
```
# products/edit.blade.php
```
<div class="mb-2">
    <label>Category</label>
    <select name="categories" class="form-control" required>
        @foreach($categories as $category)
            <option value="{{ $category->name }}"
                {{ $product->categories == $category->name ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
```
# FINAL STEP: Run Project
php artisan serve


Open:
```
http://127.0.0.1:8000/products
```

<img width="1505" height="885" alt="image" src="https://github.com/user-attachments/assets/cb1157d5-55d2-4ade-8faf-61418e579584" />
<img width="1514" height="492" alt="image" src="https://github.com/user-attachments/assets/e2faa13d-dcdc-4ac8-9259-f76a41bda924" />
