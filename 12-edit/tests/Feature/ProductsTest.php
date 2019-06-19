<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private function create_user($is_admin = 0) {
        $this->user = factory(User::class)->create([
            'email' => ($is_admin) ? 'admin@admin.com' : 'user@user.com',
            'password' => bcrypt('password123'),
            'is_admin' => $is_admin,
        ]);
    }

    public function test_homepage_contains_empty_products_table()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $product = Product::create([
            'name' => 'Product 10000',
            'price' => 99.99
        ]);

        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products found');

        $view_products = $response->viewData('products');
        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        $products = factory(Product::class, 11)->create(['price' => 9.99]);

        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertDontSee($products->last()->name);
    }



    public function test_admin_can_see_product_create_button()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products');

        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_non_admin_user_cannot_see_product_create_button()
    {
        $this->create_user(0);
        $response = $this->actingAs($this->user)->get('products');

        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }


    public function test_admin_can_access_products_create_page()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products/create');

        $response->assertStatus(200);
    }

    public function test_non_admin_user_cannot_access_products_create_page()
    {
        $this->create_user(0);
        $response = $this->actingAs($this->user)->get('products/create');

        $response->assertStatus(403);
    }


    public function test_store_product_exists_in_database()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->post('products', ['name' => 'New Product', 'price' => 99.99]);

        $response->assertRedirect('products');

        $this->assertDatabaseHas('products', ['name' => 'New Product', 'price' => 99.99]);

        $product = Product::orderBy('id', 'desc')->first();
        $this->assertEquals('New Product', $product->name);
        $this->assertEquals(99.99, $product->price);
    }


    public function test_edit_product_form_contains_correct_name_and_price()
    {
        $this->create_user(1);
        $product = factory(Product::class)->create();

        $response = $this->actingAs($this->user)->get('/products/'.$product->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('value="'.$product->name.'"');
        $response->assertSee('value="'.$product->price.'"');
    }

}
