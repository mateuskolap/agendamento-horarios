<?php

use App\Http\Controllers\Scheduling\SchedulingController;

Route::get('/schedulings', [SchedulingController::class, 'index'])->name('schedulings');

Route::prefix('/schedulings')->name('schedulings.')->group(function () {
    Route::get('/', [SchedulingController::class, 'index'])->name('index');
    Route::get('/create', [SchedulingController::class, 'create'])->name('create');
    Route::post('/', [SchedulingController::class, 'store'])->name('store');
    Route::patch('/{scheduling}', [SchedulingController::class, 'updateStatus'])
        ->middleware('can:schedulings.update-status')
        ->name('update-status');
});
