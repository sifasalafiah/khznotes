<?php

namespace App\Http\Controllers;

use App\Models\SecretNote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SecretNoteController extends Controller
{
    /**
     * Dashboard / Generator view for creating notes and checking responses
     */
    public function index()
    {
        $notes = SecretNote::latest()->get();
        return view('admin', compact('notes'));
    }

    /**
     * API to create a new secret note with token
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'nullable|string|max:100',
            'creator_name'   => 'nullable|string|max:100',
            'creator_hint'   => 'nullable|string|max:255',
            'room_context'   => 'nullable|string|max:150',
        ]);

        $token = Str::random(12);

        $note = SecretNote::create([
            'token'          => $token,
            'recipient_name' => ($validated['recipient_name'] ?? null) ?: 'Someone Special',
            'creator_name'   => ($validated['creator_name'] ?? null) ?: 'Someone nearby',
            'creator_hint'   => ($validated['creator_hint'] ?? null) ?: 'Someone in the same room.',
            'room_context'   => ($validated['room_context'] ?? null) ?: 'Same Room',
            'status'         => 'created',
            'is_destroyed'   => false,
        ]);

        return response()->json([
            'success' => true,
            'note'    => $note,
            'url'     => route('secret.show', ['token' => $note->token]),
        ]);
    }

    /**
     * Show the confidential note to the recipient
     */
    public function show($token)
    {
        $note = SecretNote::where('token', $token)->first();

        if (!$note) {
            return view('secret', [
                'noteData' => [
                    'notFound'    => true,
                    'isDestroyed' => true,
                    'token'       => $token,
                ]
            ]);
        }

        // Check if already destroyed
        if ($note->is_destroyed) {
            return view('secret', [
                'noteData' => [
                    'notFound'    => false,
                    'isDestroyed' => true,
                    'token'       => $note->token,
                    'destroyedAt' => $note->destroyed_at ? $note->destroyed_at->toIso8601String() : null,
                ]
            ]);
        }

        // First time opening: record opened_at and status in MySQL
        if (!$note->opened_at) {
            $note->update([
                'opened_at' => now(),
                'status'    => 'opened',
            ]);

            $this->notifyTelegram("👀 <b>Note Opened!</b>\nShe just opened your private note.");
        }

        return view('secret', [
            'noteData' => [
                'id'            => $note->id,
                'token'         => $note->token,
                'recipientName' => $note->recipient_name,
                'creatorName'   => $note->creator_name,
                'creatorHint'   => $note->creator_hint,
                'roomContext'   => $note->room_context,
                'status'        => $note->status,
                'currentStep'   => $note->current_step ?? 0,
                'isDestroyed'   => false,
                'openedAt'      => $note->opened_at ? $note->opened_at->toIso8601String() : null,
            ]
        ]);
    }

    /**
     * Update recipient choice / status in real-time
     */
    public function updateStatus(Request $request, $token)
    {
        $note = SecretNote::where('token', $token)->firstOrFail();

        if ($note->is_destroyed) {
            return response()->json([
                'success' => false,
                'message' => 'This note has already expired and destroyed.'
            ], 410);
        }

        $validated = $request->validate([
            'status'              => 'nullable|string|in:opened,single,taken,submitted,destroyed',
            'current_step'        => 'nullable|integer|min:0|max:10',
            'relationship_choice' => 'nullable|string|max:50',
            'contact_type'        => 'nullable|string|max:50',
            'contact_value'       => 'nullable|string|max:150',
            'note_from_recipient' => 'nullable|string|max:500',
        ]);

        $updateData = [];

        if (!empty($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }
        if (isset($validated['current_step'])) {
            $updateData['current_step'] = $validated['current_step'];
        }
        if (isset($validated['relationship_choice'])) {
            $updateData['relationship_choice'] = $validated['relationship_choice'];
        }
        if (isset($validated['contact_type'])) {
            $updateData['contact_type'] = $validated['contact_type'];
        }
        if (isset($validated['contact_value'])) {
            $updateData['contact_value'] = $validated['contact_value'];
        }
        if (isset($validated['note_from_recipient'])) {
            $updateData['note_from_recipient'] = $validated['note_from_recipient'];
        }

        $note->update($updateData);

        if (!empty($validated['status'])) {
            if ($validated['status'] === 'single') {
                $this->notifyTelegram("✨ <b>Vibe Check Passed!</b>\nShe selected <b>Single & happy to connect</b>!");
            } elseif ($validated['status'] === 'taken') {
                $this->notifyTelegram("🌿 <b>Polite Exit</b>\nShe selected <b>Taken</b>. The note is burning gracefully.");
            } elseif ($validated['status'] === 'submitted') {
                $contactVal = $note->contact_value;
                $contactType = strtoupper($note->contact_type ?? 'CONTACT');
                $noteText = $note->note_from_recipient ? "\nNote: <i>\"{$note->note_from_recipient}\"</i>" : "";
                $this->notifyTelegram("🎉 <b>Contact Received!</b>\n{$contactType}: <b>{$contactVal}</b>{$noteText}");
            }
        }

        return response()->json([
            'success' => true,
            'note'    => $note->fresh(),
        ]);
    }

    /**
     * Send instant Telegram notification if configured
     */
    protected function notifyTelegram(string $message): void
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId   = env('TELEGRAM_CHAT_ID');

        if (!$botToken || !$chatId) {
            return;
        }

        try {
            Http::timeout(3)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Throwable $e) {
            // Silently fail if no internet/telegram issue so user experience is not affected
        }
    }

    /**
     * Permanently trigger auto-destroy
     */
    public function destroy($token)
    {
        $note = SecretNote::where('token', $token)->firstOrFail();

        $note->update([
            'is_destroyed' => true,
            'destroyed_at' => now(),
            'status'       => 'destroyed',
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Note burned and purged permanently.',
            'destroyed_at' => $note->destroyed_at->toIso8601String(),
        ]);
    }

    /**
     * API for Admin to get live updates
     */
    public function liveNotes()
    {
        $notes = SecretNote::latest()->get();
        return response()->json($notes);
    }
}

