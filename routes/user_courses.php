<?php

use App\Http\Controllers\CourseUserController;

Route::middleware('can:users.courses.index')->prefix('/{user}/courses')->name('courses.')->group(function () {
    Route::get('/', [CourseUserController::class, 'index'])->name('index');
});
