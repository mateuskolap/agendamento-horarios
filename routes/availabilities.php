<?php

use App\Http\Controllers\AvailabilityController;

Route::middleware('can:availabilities.index')->prefix('availabilities')->name('availabilities.')->group(function () {
    Route::get('/', [AvailabilityController::class, 'index'])->name('index');
});
