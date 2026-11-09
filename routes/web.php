<?php

use App\Http\Controllers\SecretNoteController;
use Illuminate\Support\Facades\Route;

// Root URL is blank / 404 (Discrete disguise)
Route::get('/', function () {
    abort(404);
});

// Protected Studio Dashboard
Route::get('/studio', [SecretNoteController::class, 'index'])->name('studio.index');
Route::post('/studio/login', [SecretNoteController::class, 'login'])->name('studio.login.submit');
Route::post('/studio/logout', [SecretNoteController::class, 'logout'])->name('studio.logout');

// Studio APIs (Protected by session)
Route::post('/api/notes', [SecretNoteController::class, 'store'])->name('admin.notes.store');
Route::get('/api/notes/live', [SecretNoteController::class, 'liveNotes'])->name('admin.notes.live');

// Confidential Note View (The QR Code target - /private)
Route::get('/private/{token}', [SecretNoteController::class, 'show'])->name('private.show');
Route::post('/api/private/{token}/status', [SecretNoteController::class, 'updateStatus'])->name('private.update');
Route::post('/api/private/{token}/destroy', [SecretNoteController::class, 'destroy'])->name('private.destroy');

// Backward compatibility aliases
Route::get('/secret/{token}', function ($token) {
    return redirect()->route('private.show', ['token' => $token]);
})->name('secret.show');
Route::post('/api/secret/{token}/status', [SecretNoteController::class, 'updateStatus']);
Route::post('/api/secret/{token}/destroy', [SecretNoteController::class, 'destroy']);
