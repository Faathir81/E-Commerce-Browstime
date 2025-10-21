<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_upload_list_and_delete_product_images()
    {
        Storage::fake('public');

        $admin = User::factory()->create();

        // Buat product dulu (admin)
        $product = $this->actingAs($admin)->postJson('/admin/products', [
            'name' => 'Matcha',
            'slug' => 'matcha',
            'price' => 18000,
            'estimated_days' => 2,
        ])->assertCreated()->json('data');

        $pid = $product['id'];

        // LIST awal kosong (public)
        $this->getJson("/catalog/products/{$pid}/images")
            ->assertOk()->assertJsonCount(0, 'data');

        // UPLOAD
        $file = UploadedFile::fake()->image('matcha.jpg', 600, 400);
        $this->actingAs($admin)->postJson("/admin/products/{$pid}/images", [
            'image' => $file,
            'sort_order' => 1,
        ])->assertCreated()->assertJsonStructure(['data' => ['id', 'url', 'sort_order']]);

        // LIST berisi 1
        $list = $this->getJson("/catalog/products/{$pid}/images")
            ->assertOk()->json('data');

        $this->assertCount(1, $list);
        $imageId = $list[0]['id'];

        // DELETE
        $this->actingAs($admin)->deleteJson("/admin/products/{$pid}/images/{$imageId}")
            ->assertNoContent();

        // LIST kembali kosong
        $this->getJson("/catalog/products/{$pid}/images")
            ->assertOk()->assertJsonCount(0, 'data');
    }
}
