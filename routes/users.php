<?php

use App\Http\Controllers\UserController;

Route::middleware('can:users.index')->prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])
        ->middleware('can:users.create')
        ->name('store');
    Route::put('/{user}', [UserController::class, 'update'])
        ->middleware('can:users.update')
        ->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->middleware('can:users.delete')
        ->name('destroy');
});
