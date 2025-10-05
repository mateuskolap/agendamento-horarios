<?php

use App\Http\Controllers\CourseUserController;

Route::middleware('can:users.index')->prefix('/{user}')->name('courses.')->group(function () {
    Route::get('/courses', [CourseUserController::class, 'index'])->name('index');
    Route::delete('/courses/{course}', [CourseUserController::class, 'destroy'])->name('destroy');
});
