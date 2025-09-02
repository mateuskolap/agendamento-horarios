<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])
        ->middleware('can:courses.index')
        ->name('index');
});
