<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConfessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| Web Routes – KIU Confessions
|--------------------------------------------------------------------------
|
| Public routes: anyone can browse approved confessions & submit new ones.
| Admin routes: protected by auth + admin middleware.
|
*/

// ─── Authentication ───────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', [ConfessionController::class, 'index'])->name('confessions.index');

Route::get('/confessions/create', [ConfessionController::class, 'create'])->name('confessions.create');
Route::post('/confessions', [ConfessionController::class, 'store'])->name('confessions.store');

Route::post('/confessions/{confession}/like', [LikeController::class, 'toggle'])->name('confessions.like');
Route::post('/confessions/{confession}/comments', [CommentController::class, 'store'])->name('confessions.comments.store');

Route::get('/confessions/{confession}', [ConfessionController::class, 'show'])->name('confessions.show');

// ─── Admin Routes (auth + admin middleware) ───────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/{confession}/approve', [AdminController::class, 'approve'])->name('admin.approve');
    Route::patch('/{confession}/reject', [AdminController::class, 'reject'])->name('admin.reject');
    Route::get('/{confession}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{confession}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{confession}', [AdminController::class, 'destroy'])->name('admin.destroy');
});
