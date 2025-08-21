<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_should_register_a_user_with_valid_credentials()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function test_should_not_register_a_user_with_invalid_credentials()
    {
        $userData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_should_not_register_a_user_with_existing_email()
    {
        $user = User::factory()->create();

        $userData = [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_should_login_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $credentials);

        $response->assertStatus(200)
            ->assertJsonStructure(['accessToken', '_links']);
    }

    public function test_should_not_login_a_user_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'invalid-password',
        ];

        $response = $this->postJson(route('auth.login'), $credentials);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_should_not_login_a_user_if_email_does_not_exist()
    {
        $credentials = [
            'email' => 'noexist@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $credentials);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }


    public function test_should_logout_an_authenticated_user_and_revoke_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [], 'sanctum');

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully.',
            ]);

        $this->assertCount(0, $user->tokens()->get());
    }

    public function test_should_allow_authenticated_user_to_access_own_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson(route('auth.me'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_should_not_allow_unauthenticated_user_to_access_me_endpoint()
    {
        $response = $this->getJson(route('auth.me'));

        $response->assertStatus(401);
    }
}
