<?php

use App\Http\Controllers\Scheduling\SchedulingController;

Route::get('/schedulings', [SchedulingController::class, 'index'])->name('schedulings');

Route::prefix('/schedulings')->name('schedulings.')->group(function () {
    Route::get('/create', [SchedulingController::class, 'create'])->name('create');
    Route::post('/', [SchedulingController::class, 'store'])->name('store');
});
