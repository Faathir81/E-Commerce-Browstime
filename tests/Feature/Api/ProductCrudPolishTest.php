<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Catalog\ProductService;

class ProductCrudPolishTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_success()
    {
        $category = Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);

        $payload = [
            'name' => 'Product 1',
            'slug' => 'product-1',
            'price' => 1000,
            'category_id' => $category->id,
        ];

        $res = $this->postJson('/products', $payload);
        $res->assertStatus(201);
        $res->assertJsonStructure([
            'data' => [
                'id', 'name', 'slug', 'price', 'category', 'images', 'makeable_qty'
            ]
        ]);
    }

    public function test_store_fails_when_slug_not_unique()
    {
        $category = Category::create(['name' => 'Cat B', 'slug' => 'cat-b']);

        $existing = Product::create([
            'name' => 'Existing',
            'slug' => 'dup-slug',
            'price' => 500,
        ]);
        $existing->categories()->attach($category->id);

        $payload = [
            'name' => 'New Prod',
            'slug' => 'dup-slug',
            'price' => 800,
            'category_id' => $category->id,
        ];

        $res = $this->postJson('/products', $payload);
        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['slug']);
    }

    public function test_store_fails_with_invalid_category()
    {
        $payload = [
            'name' => 'NoCat',
            'slug' => 'no-cat',
            'price' => 200,
            'category_id' => 999999,
        ];

        $res = $this->postJson('/products', $payload);
        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['category_id']);
    }

    public function test_update_success()
    {
        $category = Category::create(['name' => 'Cat C', 'slug' => 'cat-c']);
        $product = Product::create(['name' => 'P', 'slug' => 'p-1', 'price' => 100]);
        $product->categories()->attach($category->id);

        $payload = ['name' => 'P Updated', 'price' => 150];

        $res = $this->patchJson("/products/{$product->id}", $payload);
        $res->assertStatus(200);
        $res->assertJsonPath('data.name', 'P Updated');
        $res->assertJsonPath('data.price', 150);
    }

    public function test_update_fails_on_slug_collision()
    {
        $category = Category::create(['name' => 'Cat D', 'slug' => 'cat-d']);

        $a = Product::create(['name' => 'A', 'slug' => 'slug-a', 'price' => 10]);
        $b = Product::create(['name' => 'B', 'slug' => 'slug-b', 'price' => 20]);
        $a->categories()->attach($category->id);
        $b->categories()->attach($category->id);

        $payload = ['slug' => 'slug-b'];

        $res = $this->patchJson("/products/{$a->id}", $payload);
        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['slug']);
    }

    public function test_show_not_found()
    {
        $res = $this->getJson('/products/999999');
        $res->assertStatus(404);
        $res->assertJson(['message' => 'Product not found']);
    }

    public function test_delete_not_found()
    {
        $res = $this->deleteJson('/products/999999');
        $res->assertStatus(404);
    }

    public function test_delete_conflict_when_images_present()
    {
        $category = Category::create(['name' => 'Cat E', 'slug' => 'cat-e']);
        $product = Product::create(['name' => 'WithImg', 'slug' => 'with-img', 'price' => 50]);
        $product->categories()->attach($category->id);

        ProductImage::create([
            'product_id' => $product->id,
            'url' => 'image.jpg',
            'sort_order' => 0,
        ]);

        $res = $this->deleteJson("/products/{$product->id}");
        $res->assertStatus(409);
        $res->assertJsonStructure(['message', 'details' => ['images_count']]);
        $this->assertTrue($res->json('details.images_count') >= 1);
    }

    public function test_delete_conflict_simulated_fk_throws_409()
    {
        $category = Category::create(['name' => 'Cat F', 'slug' => 'cat-f']);
        $product = Product::create(['name' => 'WithFK', 'slug' => 'with-fk', 'price' => 60]);
        $product->categories()->attach($category->id);

        // mock ProductService to simulate FK DB-level constraint (QueryException with errorInfo[1] = 1451)
        $mock = \Mockery::mock(ProductService::class);
        $previous = new class extends \Exception {
            public $errorInfo = [null, 1451, 'foreign key constraint fails'];
        };

        $qex = new \Illuminate\Database\QueryException(
            'mysql',                           // connection name
            'delete from products where id = ?', // SQL (bebas)
            [$product->id],                    // bindings
            $previous                          // previous throwable
        );
        $mock->shouldReceive('destroy')->once()->andThrow($qex);
        $this->app->instance(ProductService::class, $mock);

        $res = $this->deleteJson("/products/{$product->id}");
        $res->assertStatus(409);
        $res->assertJson(['message' => 'Product is referenced by other records and cannot be deleted.']);
    }
}