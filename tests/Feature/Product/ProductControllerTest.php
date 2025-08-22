<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /** INDEX */
    public function test_should_list_products_ordered_by_price_asc()
    {
        $this->actingAsUser();

        Product::factory()->create(['name' => 'TV', 'price' => 500]);
        Product::factory()->create(['name' => 'Sofá', 'price' => 100]);

        $response = $this->getJson(route('products.index', [
            'sort_by' => 'price',
            'order' => 'asc'
        ]));

        $response->assertStatus(200);
        $this->assertEquals('Sofá', $response['data'][0]['name']);
    }

    public function test_should_return_empty_list_if_category_does_not_exist()
    {
        $this->actingAsUser();

        Product::factory()->create(['name' => 'TV']);

        $response = $this->getJson(route('products.index', [
            'category' => 'Inexistente'
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_should_respect_per_page_parameter()
    {
        $this->actingAsUser();

        Product::factory()->count(15)->create();

        $response = $this->getJson(route('products.index', [
            'per_page' => 5
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** STORE */
    public function test_should_create_product_with_new_category()
    {
        $this->actingAsUser();

        $response = $this->postJson(route('products.store'), [
            'name' => 'Notebook',
            'description' => 'Notebook Gamer',
            'quantity' => 10,
            'price' => 2500,
            'category_name' => 'Eletrônicos'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Notebook']);

        $this->assertDatabaseHas('categories', ['name' => 'Eletrônicos']);
    }

    public function test_should_not_create_product_with_negative_price()
    {
        $this->actingAsUser();

        $response = $this->postJson(route('products.store'), [
            'name' => 'Notebook',
            'quantity' => 10,
            'price' => -50
        ]);

        $response->assertStatus(422);
    }

    public function test_should_not_create_product_when_not_authenticated()
    {
        $response = $this->postJson(route('products.store'), [
            'name' => 'Notebook',
            'quantity' => 10,
            'price' => 1000
        ]);

        $response->assertStatus(401);
    }

    /** SHOW */
    public function test_should_return_404_if_product_not_found()
    {
        $this->actingAsUser();

        $response = $this->getJson(route('products.show', 999));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Product not found']);
    }

    /** UPDATE */
    public function test_should_update_product_name()
    {
        $this->actingAsUser();

        $product = Product::factory()->create(['name' => 'Old TV']);

        $response = $this->putJson(route('products.update', $product->id), [
            'name' => 'Smart TV'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Smart TV']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Smart TV']);
    }

    public function test_should_update_category_to_null()
    {
        $this->actingAsUser();

        $category = Category::factory()->create(['name' => 'Eletrônicos']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->putJson(route('products.update', $product->id), [
            'category_name' => null
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'category_id' => null]);
    }

    public function test_should_not_update_product_with_negative_quantity()
    {
        $this->actingAsUser();

        $product = Product::factory()->create();

        $response = $this->putJson(route('products.update', $product->id), [
            'quantity' => -5
        ]);

        $response->assertStatus(422);
    }

    public function test_should_return_404_when_updating_nonexistent_product()
    {
        $this->actingAsUser();

        $response = $this->putJson(route('products.update', 999), [
            'name' => 'Does not exist'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Product not found']);
    }

    /** DESTROY */
    public function test_should_return_404_when_deleting_nonexistent_product()
    {
        $this->actingAsUser();

        $response = $this->deleteJson(route('products.destroy', 999));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Product not found']);
    }

    public function test_should_not_delete_product_when_not_authenticated()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product->id));

        $response->assertStatus(401);
    }
}
