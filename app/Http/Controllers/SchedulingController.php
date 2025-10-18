<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SchedulingController extends Controller
{
    public function index()
    {
        return Inertia::render('schedulings/Index');
    }
}
