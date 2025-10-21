<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function public_can_list_products_empty()
    {
        $res = $this->getJson('/catalog/products');

        $res->assertOk()
            ->assertJsonStructure(['data', 'meta'])
            ->assertJsonPath('meta.total', 0);
    }

    /** @test */
    public function admin_can_create_update_show_and_delete_product()
    {
        $admin = User::factory()->create();

        // Buat kategori (admin)
        $cat = $this->actingAs($admin)->postJson('/admin/categories', [
            'name' => 'Cookies',
            'slug' => 'cookies',
        ])->assertCreated()->json('data');

        // CREATE product
        $payload = [
            'name'           => 'Choco Chip',
            'slug'           => 'choco-chip',
            'short_label'    => 'Best Seller',
            'description'    => 'Crunchy choco chip cookies',
            'price'          => 15000,
            'estimated_days' => 2,
            'is_best_seller' => true,
            'is_active'      => true,
            'categories'     => [$cat['id']],
        ];

        $create = $this->actingAs($admin)->postJson('/admin/products', $payload)
            ->assertCreated()
            ->assertJsonStructure(['message', 'data' => ['id', 'name', 'slug']])
            ->json('data');

        $id = $create['id'];

        // SHOW public
        $this->getJson("/catalog/products/{$id}")
            ->assertOk()
            ->assertJsonPath('data.slug', 'choco-chip');

        // UPDATE product
        $update = $this->actingAs($admin)->patchJson("/admin/products/{$id}", [
            'name' => 'Choco Chip Deluxe',
        ])->assertOk()->json('data');

        $this->assertEquals('Choco Chip Deluxe', $update['name']);

        // DELETE product (tanpa images -> 204)
        $this->actingAs($admin)->deleteJson("/admin/products/{$id}")
            ->assertNoContent();

        // SHOW after delete -> 404
        $this->getJson("/catalog/products/{$id}")
            ->assertNotFound();
    }

    /** @test */
    public function slug_must_be_unique_on_store()
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->postJson('/admin/products', [
            'name' => 'A',
            'slug' => 'unik-slug',
            'price' => 1000,
            'estimated_days' => 1,
        ])->assertCreated();

        $this->actingAs($admin)->postJson('/admin/products', [
            'name' => 'B',
            'slug' => 'unik-slug', // sama
            'price' => 2000,
            'estimated_days' => 1,
        ])->assertUnprocessable(); // 422
    }

    /** @test */
    public function delete_nonexistent_product_returns_404()
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->deleteJson('/admin/products/999999')
            ->assertNotFound();
    }
}
