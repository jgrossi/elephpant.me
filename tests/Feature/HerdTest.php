<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HerdTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_user_herd()
    {
        $user = User::factory()->create([
            'x_handle' => 'testuser',
        ]);

        $response = $this->get('/herd/testuser');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_returns_404_for_nonexistent_user()
    {
        $response = $this->get('/herd/nonexistent');

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_view_my_herd()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/my-herd');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_my_herd()
    {
        $response = $this->get('/my-herd');

        $response->assertRedirect('/login');
    }
}
