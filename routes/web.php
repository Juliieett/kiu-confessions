<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfessionController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes – KIU Confessions
|--------------------------------------------------------------------------
|
| Public routes: anyone can browse approved confessions & submit new ones.
| Admin routes: protected by a simple secret key stored in .env
|               (no login required per project spec).
|
*/

// ─── Public Routes ────────────────────────────────────────────────────────────

// ─── Public Routes ───────────────────────────────────────────

Route::get('/', [ConfessionController::class, 'index'])->name('confessions.index');

// ⚠️ create MUST come before /{confession} wildcard
Route::get('/confessions/create', [ConfessionController::class, 'create'])->name('confessions.create');
Route::post('/confessions', [ConfessionController::class, 'store'])->name('confessions.store');

Route::get('/confessions/{confession}', [ConfessionController::class, 'show'])->name('confessions.show');

// ─── Admin Routes ─────────────────────────────────────────────────────────────

// Admin dashboard – list ALL confessions with filters
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Approve a confession
Route::patch('/admin/{confession}/approve', [AdminController::class, 'approve'])->name('admin.approve');

// Reject a confession
Route::patch('/admin/{confession}/reject', [AdminController::class, 'reject'])->name('admin.reject');

// Edit confession (admin can fix text before approving)
Route::get('/admin/{confession}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/admin/{confession}', [AdminController::class, 'update'])->name('admin.update');

// Delete confession
Route::delete('/admin/{confession}', [AdminController::class, 'destroy'])->name('admin.destroy');
