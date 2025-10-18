<?php

namespace App\Http\Controllers\Scheduling;

use Inertia\Inertia;

class SchedulingController extends Controller
{
    public function index()
    {
        return Inertia::render('schedulings/Index');
    }
}
