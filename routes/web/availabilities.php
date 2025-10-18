<?php

use App\Http\Controllers\AvailabilityController;

Route::middleware('can:availabilities.index')->prefix('/availabilities')->name('availabilities.')->group(function () {
    Route::get('/', [AvailabilityController::class, 'index'])->name('index');
    Route::post('/', [AvailabilityController::class, 'store'])->name('store');
    Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('destroy');
    Route::post('/{availability}', [AvailabilityController::class, 'update'])->name('update');
});
