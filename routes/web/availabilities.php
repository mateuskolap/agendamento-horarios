<?php

use App\Http\Controllers\Availability\AvailabilityController;

Route::prefix('/users/{user}/available-times')->name('users.available_times.')->group(function () {
    Route::get('/', [AvailabilityController::class, 'availableTimesForRequestedOnDay'])->name('get');
});

Route::middleware('can:availabilities.index')->prefix('/availabilities')->name('availabilities.')->group(function () {
    Route::get('/', [AvailabilityController::class, 'index'])->name('index');
    Route::post('/', [AvailabilityController::class, 'store'])->name('store');
    Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('destroy');
});
