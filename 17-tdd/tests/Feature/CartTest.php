<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
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

    public function test_button_add_to_cart_is_shown()
    {
        $this->create_user(0);

        factory(Product::class)->create();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertSee('Add to cart');
    }
}
