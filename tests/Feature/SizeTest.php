<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeTest extends TestCase
{
    use RefreshDatabase;

    public function test_size_can_be_created()
    {
        $response = $this->post(route('sizes.store'), [
            'name' => 'Large',
            'code' => 'L',
            'description' => 'Large size',
        ]);

        $response->assertRedirect(route('sizes.index'));
        $this->assertDatabaseHas('sizes', ['name' => 'Large', 'code' => 'L']);
    }

    public function test_size_requires_name()
    {
        $response = $this->post(route('sizes.store'), []);

        $response->assertSessionHasErrors('name');
    }

    public function test_size_cannot_be_deleted_if_it_has_products()
    {
        $category = Category::create(['name' => 'Electronics']);
        $size = Size::create(['name' => 'Medium']);
        Product::create([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'name' => 'Widget',
            'details' => 'desc',
            'image' => 'x.png',
            'color' => 'Red',
            'price' => 10,
            'stock_quantity' => 1,
            'min_stock' => 0,
        ]);

        $response = $this->delete(route('sizes.destroy', $size));

        $response->assertRedirect(route('sizes.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('sizes', ['id' => $size->id]);
    }
}
