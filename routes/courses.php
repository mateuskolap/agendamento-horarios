<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])
        ->middleware('can:courses.index')
        ->name('index');
    Route::post('/', [CourseController::class, 'store'])
        ->middleware('can:courses.create')
        ->name('store');
    Route::put('/', [CourseController::class, 'update'])
        ->middleware('can:courses.update')
        ->name('update');
    Route::delete('/{course}', [CourseController::class, 'destroy'])
        ->middleware('can:courses.delete')
        ->name('destroy');
});
