<?php

namespace Tests\Feature;

use App\Models\SecretNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_note_with_custom_slug(): void
    {
        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/notes', [
                'custom_slug' => 'sarah-special',
                'recipient_name' => 'Sarah',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'note' => [
                    'token' => 'sarah-special',
                    'recipient_name' => 'Sarah',
                ],
            ]);

        $this->assertDatabaseHas('secret_notes', [
            'token' => 'sarah-special',
        ]);
    }

    public function test_can_view_note_at_private_route(): void
    {
        SecretNote::create([
            'token' => 'sarah-special',
            'recipient_name' => 'Sarah',
            'creator_name' => 'Me',
            'creator_hint' => 'Room',
            'room_context' => 'Same Room',
            'status' => 'created',
        ]);

        $response = $this->get('/private/sarah-special');
        $response->assertStatus(200);
    }

    public function test_legacy_secret_route_redirects_to_private(): void
    {
        $response = $this->get('/secret/sarah-special');
        $response->assertRedirect('/private/sarah-special');
    }

    public function test_custom_slug_rejects_duplicate(): void
    {
        SecretNote::create([
            'token' => 'sarah-special',
            'recipient_name' => 'Sarah',
            'creator_name' => 'Me',
            'creator_hint' => 'Room',
            'room_context' => 'Same Room',
            'status' => 'created',
        ]);

        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/notes', [
                'custom_slug' => 'sarah-special',
            ]);

        $response->assertStatus(422);
    }
}
