<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:courses.index')->prefix('/courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])
        ->name('index');
    Route::post('/', [CourseController::class, 'store'])
        ->middleware('can:courses.create')
        ->name('store');
    Route::put('/{course}', [CourseController::class, 'update'])
        ->middleware('can:courses.update')
        ->name('update');
    Route::delete('/{course}', [CourseController::class, 'destroy'])
        ->middleware('can:courses.delete')
        ->name('destroy');
});
