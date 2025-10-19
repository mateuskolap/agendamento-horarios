<?php

use App\Http\Controllers\Scheduling\SchedulingController;

Route::get('/scheduling', [SchedulingController::class, 'index'])->name('schedulings');
