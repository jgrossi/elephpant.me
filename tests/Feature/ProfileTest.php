<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_profile_page()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_update_profile()
    {
        $user = User::factory()->create([
            'username' => 'originalusername',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
            'username' => $user->username,
            'country_code' => 'USA',
            'x_handle' => 'newhandle',
            'mastodon' => '@user@mastodon.social',
            'bluesky' => '@user.bsky.social',
        ]);

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'x_handle' => 'newhandle',
        ]);
    }
}
