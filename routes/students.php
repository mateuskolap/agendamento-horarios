<?php

use App\Http\Controllers\StudentController;

Route::prefix('/students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])
        ->middleware('can:students.index')
        ->name('index');
    Route::post('/', [StudentController::class, 'store'])
        ->middleware('can:students.create')
        ->name('store');
    Route::put('/', [StudentController::class, 'update'])
        ->middleware('can:students.update')
        ->name('update');
    Route::delete('/', [StudentController::class, 'destroy'])
        ->middleware('can:students.delete')
        ->name('destroy');
});
