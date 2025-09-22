<?php

use App\Http\Middleware\EnsurePasswordChange;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified', EnsurePasswordChange::class])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    require __DIR__ . '/settings.php';
    require __DIR__ . '/courses.php';
    require __DIR__ . '/users.php';
});
