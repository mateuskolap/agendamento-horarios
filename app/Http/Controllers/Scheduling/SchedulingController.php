<?php

namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SchedulingController extends Controller
{
    public function index()
    {
        return Inertia::render('schedulings/Index');
    }
}
