<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_login_redirects_successfully()
    {
        // Create a user
        factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'password123']);

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }


    public function test_authenticated_user_can_access_products_table()
    {
        // Create a user
        $user = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_products_table()
    {
        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

}
