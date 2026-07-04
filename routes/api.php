<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Login resolves the tenant from the email address itself (see
// AuthenticationService::loginForApi), so it does NOT go through the
// tenant.header middleware - the client doesn't know its client_code
// until after this call succeeds.
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('api.login');

// Every authenticated call must resolve its tenant from the
// X-Client-Code header BEFORE Sanctum tries to load the token, hence
// tenant.header preceding auth:sanctum in the middleware list.
Route::middleware(['tenant.header', 'auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
