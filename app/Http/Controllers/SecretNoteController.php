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
    public function index(Request $request)
    {
        if (!$request->session()->get('studio_auth')) {
            return view('login');
        }

        $notes = SecretNote::latest()->get();
        return view('admin', compact('notes'));
    }

    /**
     * Authenticate studio access via passcode
     */
    public function login(Request $request)
    {
        $passcode = $request->input('passcode');
        $validPasscode = env('STUDIO_PASSCODE', 'khz2026');

        if ($passcode === $validPasscode) {
            $request->session()->put('studio_auth', true);
            return redirect()->route('studio.index');
        }

        return redirect()->route('studio.index')->with('error', 'Incorrect passcode. Access denied.');
    }

    /**
     * Logout and lock studio
     */
    public function logout(Request $request)
    {
        $request->session()->forget('studio_auth');
        return redirect()->route('studio.index');
    }

    /**
     * API to create a new secret note with token
     */
    public function store(Request $request)
    {
        if (!$request->session()->get('studio_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please login to studio.'], 401);
        }
        $validated = $request->validate([
            'custom_slug'    => 'nullable|string|max:64|regex:/^[a-zA-Z0-9_\-]+$/|unique:secret_notes,token',
            'custom_url'     => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:100',
            'creator_name'   => 'nullable|string|max:100',
            'creator_hint'   => 'nullable|string|max:255',
            'room_context'   => 'nullable|string|max:150',
        ], [
            'custom_slug.unique' => 'This slug is already taken. Please choose another one.',
            'custom_slug.regex'  => 'Slug can only contain letters, numbers, dashes, and underscores.',
        ]);

        $customSlug = trim($validated['custom_slug'] ?? '');
        $token = !empty($customSlug) ? Str::slug($customSlug) : Str::random(12);

        // Double check uniqueness after slugify
        if (SecretNote::where('token', $token)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This slug is already taken. Please choose another one.'
            ], 422);
        }

        $note = SecretNote::create([
            'token'          => $token,
            'custom_url'     => !empty($validated['custom_url']) ? trim($validated['custom_url']) : null,
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
            'url'     => route('notes.show', ['token' => $note->token]),
        ]);
    }

    /**
     * Update custom link/shortlink for card and QR
     */
    public function updateCustomUrl(Request $request, $token)
    {
        if (!$request->session()->get('studio_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $note = SecretNote::where('token', $token)->firstOrFail();
        $validated = $request->validate([
            'custom_url' => 'nullable|string|max:255',
        ]);

        $customUrl = !empty($validated['custom_url']) ? trim($validated['custom_url']) : null;
        $note->custom_url = $customUrl;
        $note->save();

        return response()->json([
            'success' => true,
            'note'    => $note,
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

            $this->notifyTelegram("[Note Opened]\nShe just opened your private note.");
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
                $this->notifyTelegram("[Status Update]\nShe selected: Single & happy to connect.");
            } elseif ($validated['status'] === 'taken') {
                $this->notifyTelegram("[Polite Exit]\nShe selected: Taken. The note is closing gracefully.");
            } elseif ($validated['status'] === 'submitted') {
                $contactVal = $note->contact_value;
                $contactType = strtoupper($note->contact_type ?? 'CONTACT');
                $noteText = $note->note_from_recipient ? "\nNote: \"{$note->note_from_recipient}\"" : "";
                $this->notifyTelegram("[Contact Received]\n{$contactType}: {$contactVal}{$noteText}");
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
    public function liveNotes(Request $request)
    {
        if (!$request->session()->get('studio_auth')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notes = SecretNote::latest()->get();
        return response()->json($notes);
    }

    /**
     * Reset note state or wipe test data for testing (protected by studio passcode)
     */
    public function reset(Request $request)
    {
        if (!$request->session()->get('studio_auth')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please login to studio.'], 401);
        }

        $passcode = $request->input('passcode');
        $validPasscode = env('STUDIO_PASSCODE', 'khz2026');

        if (empty($passcode) || $passcode !== $validPasscode) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect passcode. Verification failed.'
            ], 422);
        }

        $mode = $request->input('mode', 'note');
        $token = $request->input('token');

        if ($mode === 'note') {
            if (!$token) {
                return response()->json(['success' => false, 'message' => 'Note token is required for note reset.'], 422);
            }

            $note = SecretNote::where('token', $token)->first();
            if (!$note) {
                return response()->json(['success' => false, 'message' => 'Note not found.'], 404);
            }

            $note->update([
                'status'              => 'created',
                'current_step'        => 0,
                'relationship_choice' => null,
                'contact_type'        => null,
                'contact_value'       => null,
                'note_from_recipient' => null,
                'opened_at'           => null,
                'is_destroyed'        => false,
                'destroyed_at'        => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Note #{$token} has been reset to unopened state for testing.",
                'note'    => $note->fresh(),
            ]);
        }

        if ($mode === 'all_status') {
            SecretNote::query()->update([
                'status'              => 'created',
                'current_step'        => 0,
                'relationship_choice' => null,
                'contact_type'        => null,
                'contact_value'       => null,
                'note_from_recipient' => null,
                'opened_at'           => null,
                'is_destroyed'        => false,
                'destroyed_at'        => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All notes have been reset to unopened state for testing.',
            ]);
        }

        if ($mode === 'all_delete') {
            SecretNote::query()->delete();

            return response()->json([
                'success' => true,
                'message' => 'All notes have been deleted from the database.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid reset mode selected.'], 422);
    }
}

