<?php

use App\Http\Controllers\SchedulingController;

Route::get('/scheduling', [SchedulingController::class, 'index'])->name('schedulings');
