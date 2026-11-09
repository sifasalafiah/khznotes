<?php

use App\Http\Controllers\SecretNoteController;
use Illuminate\Support\Facades\Route;

// Admin & Note Generator Dashboard
Route::get('/', [SecretNoteController::class, 'index'])->name('admin.index');
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
