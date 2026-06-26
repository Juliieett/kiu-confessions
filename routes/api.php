<?php

use App\Http\Controllers\Api\ConfessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – KIU Confessions
|--------------------------------------------------------------------------
|
| JSON endpoints for confessions. Prefix: /api
|
*/

Route::apiResource('confessions', ConfessionController::class)
    ->only(['index', 'show', 'store'])
    ->names([
        'index' => 'api.confessions.index',
        'show'  => 'api.confessions.show',
        'store' => 'api.confessions.store',
    ]);
