<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_successful_response_with_stats()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'M']);

        $response = $this->get('/');

        $response->assertOk()
            ->assertViewHasAll(['totalProducts', 'totalCategories', 'totalSizes', 'lowStockProducts', 'outOfStockProducts']);

        $this->assertEquals(0, $response->viewData('totalProducts'));
    }

    public function test_dashboard_lists_low_and_out_of_stock_products()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'M']);

        Product::create([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'In Stock Item',
            'details' => 'x',
            'image' => 'x.png',
            'color' => 'Red',
            'price' => 10,
            'stock_quantity' => 100,
            'min_stock' => 5,
        ]);

        Product::create([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'Low Stock Item',
            'details' => 'x',
            'image' => 'x.png',
            'color' => 'Red',
            'price' => 10,
            'stock_quantity' => 2,
            'min_stock' => 5,
        ]);

        Product::create([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'Out Of Stock Item',
            'details' => 'x',
            'image' => 'x.png',
            'color' => 'Red',
            'price' => 10,
            'stock_quantity' => 0,
            'min_stock' => 5,
        ]);

        $response = $this->get('/');

        $response->assertOk();

        $this->assertEquals(3, $response->viewData('totalProducts'));
        $this->assertCount(1, $response->viewData('lowStockProducts'));
        $this->assertCount(1, $response->viewData('outOfStockProducts'));
    }
}
