<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function makeProductData(Category $category, Size $size, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'Test Product',
            'details' => 'A test product',
            'image' => 'default.png',
            'color' => 'Red',
            'price' => 99.99,
            'sku' => uniqid('SKU-'),
            'stock_quantity' => 5,
            'min_stock' => 10,
        ], $overrides);
    }

    public function test_product_index_returns_paginated_list()
    {
        $response = $this->get(route('products.index'));

        $response->assertOk();
    }

    public function test_product_can_be_created_with_relations()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'L']);

        $response = $this->post(route('products.store'), [
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'Laptop',
            'details' => 'Nice laptop',
            'color' => 'Black',
            'price' => 1200,
            'sku' => 'LP-001',
            'stock_quantity' => 3,
            'min_stock' => 5,
            // Uses the online image URL flow instead of a file upload
            // so tests don't write files to public/images.
            'image_url' => 'https://example.com/product.png',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Laptop',
            'category_id' => $category->id,
            'size_id' => $size->id,
            'sku' => 'LP-001',
            'stock_quantity' => 3,
        ]);
    }

    public function test_product_index_filters_by_category()
    {
        $electronics = Category::create(['name' => 'Electronics']);
        $clothing = Category::create(['name' => 'Clothing']);
        $size = Size::create(['name' => 'M']);

        Product::create($this->makeProductData($electronics, $size, ['name' => 'Laptop']));
        Product::create($this->makeProductData($clothing, $size, ['name' => 'Shirt']));

        $response = $this->get(route('products.index', ['category_id' => $electronics->id]));

        $response->assertOk();
        $this->assertEquals(1, $response->viewData('products')->total());
    }

    public function test_product_index_filters_by_search_term()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'M']);

        Product::create($this->makeProductData($category, $size, ['name' => 'Laptop Pro']));
        Product::create($this->makeProductData($category, $size, ['name' => 'Mouse']));

        $response = $this->get(route('products.index', ['search' => 'Laptop']));

        $response->assertOk();
        $this->assertEquals(1, $response->viewData('products')->total());
    }

    public function test_product_index_filters_by_price_range()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'M']);

        Product::create($this->makeProductData($category, $size, ['name' => 'Cheap', 'price' => 10]));
        Product::create($this->makeProductData($category, $size, ['name' => 'Expensive', 'price' => 500]));

        $response = $this->get(route('products.index', ['min_price' => 100]));

        $response->assertOk();
        $this->assertEquals(1, $response->viewData('products')->total());
    }

    public function test_product_show_displays_details()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'M']);
        $product = Product::create($this->makeProductData($category, $size));

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertViewHas('product', $product);
    }
}
