<?php

namespace App\Http\Controllers\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchedulingRequest;
use App\Services\Scheduling\SchedulingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SchedulingController extends Controller
{
    public function __construct(
        private readonly SchedulingService $schedulingService,
    )
    {
    }

    public function index(): Response
    {
        return Inertia::render('schedulings/Index');
    }

    public function create(): Response
    {
        return Inertia::render('schedulings/Create');
    }

    public function store(StoreSchedulingRequest $request): RedirectResponse
    {
        $storeSchedulingData = StoreSchedulingData::from($request->validated());

        try {
            $this->schedulingService->create($storeSchedulingData);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('schedulings')->with('success', 'Solicitação de agendamento encaminhada');
    }
}
