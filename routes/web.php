<?php

use App\Http\Controllers\SecretNoteController;
use Illuminate\Support\Facades\Route;

// Admin & Note Generator Dashboard
Route::get('/', [SecretNoteController::class, 'index'])->name('admin.index');
Route::post('/api/notes', [SecretNoteController::class, 'store'])->name('admin.notes.store');
Route::get('/api/notes/live', [SecretNoteController::class, 'liveNotes'])->name('admin.notes.live');

// Confidential Note View (The QR Code target)
Route::get('/secret/{token}', [SecretNoteController::class, 'show'])->name('secret.show');
Route::post('/api/secret/{token}/status', [SecretNoteController::class, 'updateStatus'])->name('secret.update');
Route::post('/api/secret/{token}/destroy', [SecretNoteController::class, 'destroy'])->name('secret.destroy');
