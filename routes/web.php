<?php

use App\Http\Controllers\SecretNoteController;
use Illuminate\Support\Facades\Route;

// Root URL: Clean, calm public disguise page
Route::get('/', function () {
    return view('landing');
});

// Protected Studio Dashboard
Route::get('/studio', [SecretNoteController::class, 'index'])->name('studio.index');
Route::post('/studio/login', [SecretNoteController::class, 'login'])->name('studio.login.submit');
Route::post('/studio/logout', [SecretNoteController::class, 'logout'])->name('studio.logout');

// Studio APIs (Protected by session)
Route::post('/api/notes', [SecretNoteController::class, 'store'])->name('admin.notes.store');
Route::get('/api/notes/live', [SecretNoteController::class, 'liveNotes'])->name('admin.notes.live');

// Confidential Note View (The QR Code target - /notes)
Route::get('/notes/{token}', [SecretNoteController::class, 'show'])->name('notes.show');
Route::post('/api/notes/{token}/status', [SecretNoteController::class, 'updateStatus'])->name('notes.update');
Route::post('/api/notes/{token}/destroy', [SecretNoteController::class, 'destroy'])->name('notes.destroy');

// Backward compatibility aliases
Route::get('/private/{token}', function ($token) {
    return redirect()->route('notes.show', ['token' => $token]);
})->name('private.show');
Route::post('/api/private/{token}/status', [SecretNoteController::class, 'updateStatus'])->name('private.update');
Route::post('/api/private/{token}/destroy', [SecretNoteController::class, 'destroy'])->name('private.destroy');

Route::get('/secret/{token}', function ($token) {
    return redirect()->route('notes.show', ['token' => $token]);
})->name('secret.show');
Route::post('/api/secret/{token}/status', [SecretNoteController::class, 'updateStatus']);
Route::post('/api/secret/{token}/destroy', [SecretNoteController::class, 'destroy']);
