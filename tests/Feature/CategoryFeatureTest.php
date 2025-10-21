<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function public_can_list_and_show_categories()
    {
        $admin = User::factory()->create();

        $cat = $this->actingAs($admin)->postJson('/admin/categories', [
            'name' => 'Cakes',
            'slug' => 'cakes',
        ])->assertCreated()->json('data');

        $this->getJson('/catalog/categories')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta'])
            ->assertJsonFragment(['slug' => 'cakes']);

        $this->getJson('/catalog/categories/'.$cat['id'])
            ->assertOk()
            ->assertJsonPath('data.slug', 'cakes');
    }

    /** @test */
    public function admin_can_update_and_delete_category_when_not_used()
    {
        $admin = User::factory()->create();

        $cat = $this->actingAs($admin)->postJson('/admin/categories', [
            'name' => 'Brownies',
            'slug' => 'brownies',
        ])->assertCreated()->json('data');

        $this->actingAs($admin)->patchJson('/admin/categories/'.$cat['id'], [
            'name' => 'Brownies Premium',
        ])->assertOk()->assertJsonPath('data.name', 'Brownies Premium');

        $this->actingAs($admin)->deleteJson('/admin/categories/'.$cat['id'])
            ->assertNoContent();
    }

    /** @test */
    public function cannot_delete_category_when_used_by_product()
    {
        $admin = User::factory()->create();

        $cat = $this->actingAs($admin)->postJson('/admin/categories', [
            'name' => 'Cookies',
            'slug' => 'cookies',
        ])->assertCreated()->json('data');

        // Create product pakai kategori ini
        $this->actingAs($admin)->postJson('/admin/products', [
            'name' => 'Oatmeal',
            'slug' => 'oatmeal',
            'price' => 10000,
            'estimated_days' => 1,
            'categories' => [$cat['id']],
        ])->assertCreated();

        // Hapus kategori -> 409
        $this->actingAs($admin)->deleteJson('/admin/categories/'.$cat['id'])
            ->assertStatus(409);
    }
}
