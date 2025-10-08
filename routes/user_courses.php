<?php

use App\Http\Controllers\CourseUserController;

Route::middleware('can:users.index')->prefix('/{user}')->name('courses.')->group(function () {
    Route::get('/courses', [CourseUserController::class, 'index'])->name('index');
    Route::post('/courses', [CourseUserController::class, 'store'])->name('store');
    Route::delete('/courses/{course}', [CourseUserController::class, 'destroy'])->name('destroy');
});
