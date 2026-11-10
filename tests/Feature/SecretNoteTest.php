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

    public function test_can_view_note_at_notes_route(): void
    {
        SecretNote::create([
            'token' => 'sarah-special',
            'recipient_name' => 'Sarah',
            'creator_name' => 'Me',
            'creator_hint' => 'Room',
            'room_context' => 'Same Room',
            'status' => 'created',
        ]);

        $response = $this->get('/notes/sarah-special');
        $response->assertStatus(200);
    }

    public function test_legacy_private_route_redirects_to_notes(): void
    {
        $response = $this->get('/private/sarah-special');
        $response->assertRedirect('/notes/sarah-special');
    }

    public function test_legacy_secret_route_redirects_to_notes(): void
    {
        $response = $this->get('/secret/sarah-special');
        $response->assertRedirect('/notes/sarah-special');
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

    public function test_can_create_note_with_custom_url(): void
    {
        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/notes', [
                'custom_slug' => 'for-khz',
                'custom_url'  => 'https://bit.ly/notes-for-you',
                'recipient_name' => 'Sarah',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'note' => [
                    'token' => 'for-khz',
                    'custom_url' => 'https://bit.ly/notes-for-you',
                ],
            ]);

        $this->assertDatabaseHas('secret_notes', [
            'token' => 'for-khz',
            'custom_url' => 'https://bit.ly/notes-for-you',
        ]);
    }

    public function test_can_update_custom_url_via_api(): void
    {
        $note = SecretNote::create([
            'token' => 'for-khz-2',
            'recipient_name' => 'Sarah',
            'creator_name' => 'Me',
            'creator_hint' => 'Room',
            'room_context' => 'Same Room',
            'status' => 'created',
        ]);

        $response = $this->withSession(['studio_auth' => true])
            ->postJson("/api/notes/{$note->token}/custom-url", [
                'custom_url' => 'https://bit.ly/notes-for-you',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'note' => [
                    'custom_url' => 'https://bit.ly/notes-for-you',
                ],
            ]);

        $this->assertDatabaseHas('secret_notes', [
            'token' => 'for-khz-2',
            'custom_url' => 'https://bit.ly/notes-for-you',
        ]);
    }

    public function test_reset_requires_valid_passcode(): void
    {
        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/studio/reset', [
                'passcode' => 'wrongpasscode',
                'mode'     => 'all_delete',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Incorrect passcode. Verification failed.',
            ]);
    }

    public function test_reset_specific_note(): void
    {
        $note = SecretNote::create([
            'token'                => 'test-reset-note',
            'recipient_name'       => 'Sarah',
            'status'               => 'submitted',
            'current_step'         => 3,
            'relationship_choice'  => 'single',
            'contact_type'         => 'instagram',
            'contact_value'        => '@sarah',
            'is_destroyed'         => true,
            'destroyed_at'         => now(),
        ]);

        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/studio/reset', [
                'passcode' => 'khz2026',
                'mode'     => 'note',
                'token'    => 'test-reset-note',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $note->refresh();
        $this->assertEquals('created', $note->status);
        $this->assertEquals(0, $note->current_step);
        $this->assertFalse((bool)$note->is_destroyed);
        $this->assertNull($note->contact_value);
    }

    public function test_reset_all_delete(): void
    {
        SecretNote::create([
            'token' => 'note-1',
            'status' => 'created',
        ]);
        SecretNote::create([
            'token' => 'note-2',
            'status' => 'created',
        ]);

        $response = $this->withSession(['studio_auth' => true])
            ->postJson('/api/studio/reset', [
                'passcode' => 'khz2026',
                'mode'     => 'all_delete',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseCount('secret_notes', 0);
    }
}
