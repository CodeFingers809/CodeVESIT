<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that login page displays correctly.
     */
    public function test_login_page_displays(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /**
     * Test that login page has gruvbox theme colors.
     */
    public function test_login_page_has_gruvbox_theme(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        // Check for gruvbox color classes or styles
        $response->assertSee('bg-gruvbox');
    }

    /**
     * Test that users can login with valid credentials.
     */
    public function test_users_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test that users cannot login with invalid password.
     */
    public function test_users_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /**
     * Test that registration page displays correctly.
     */
    public function test_registration_page_displays(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    /**
     * Test that registration page has gruvbox theme colors.
     */
    public function test_registration_page_has_gruvbox_theme(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        // Check for gruvbox color classes or styles
        $response->assertSee('bg-gruvbox');
    }

    /**
     * Test that new users can register.
     */
    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@ves.ac.in',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => 'Computer Engineering',
            'year' => 'SE',
            'division' => 'A',
            'roll_number' => '001',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@ves.ac.in',
            'name' => 'Test User',
        ]);
    }

    /**
     * Test that registration requires password confirmation.
     */
    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@ves.ac.in',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
            'department' => 'Computer Engineering',
            'year' => 'SE',
            'division' => 'A',
            'roll_number' => '001',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('password');
    }

    /**
     * Test that email must be unique on registration.
     */
    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@ves.ac.in']);

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'existing@ves.ac.in',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => 'Computer Engineering',
            'year' => 'SE',
            'division' => 'A',
            'roll_number' => '001',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test that users can logout.
     */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * Test that authentication pages have favicon.
     */
    public function test_authentication_pages_have_favicon(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('favicon.svg');
    }

    /**
     * Test that authenticated users are redirected from login.
     */
    public function test_authenticated_users_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test that authenticated users are redirected from register.
     */
    public function test_authenticated_users_redirected_from_register(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('register'));

        $response->assertRedirect(route('dashboard'));
    }
}
