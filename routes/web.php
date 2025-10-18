<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/web/auth.php';

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

Route::middleware(['auth', 'verified', 'password.change'])->group(function () {
    require __DIR__ . '/web/schedulings.php';

    require __DIR__ . '/web/settings.php';
    require __DIR__ . '/web/courses.php';
    require __DIR__ . '/web/users.php';
    require __DIR__ . '/web/availabilities.php';
});
