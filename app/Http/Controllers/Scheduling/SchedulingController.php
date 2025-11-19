<?php

namespace App\Http\Controllers\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Data\Scheduling\UpdateSchedulingStatusData;
use App\Http\Controllers\Controller;
use App\Models\Scheduling;
use App\Models\User;
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
        $user = \Auth::user();

        return Inertia::render('schedulings/Index', [
            'requested_schedulings' => Scheduling::where('requester_id', $user->id)
                ->with(['requester', 'requested', 'course'])
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get(),
            'received_schedulings' => Scheduling::where('requested_id', $user->id)
                ->with(['requester', 'requested', 'course'])
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        $user = \Auth::user();

        return Inertia::render('schedulings/Create', [
            'educators' => User::availableForSchedulingsWith($user)->select(['id', 'name', 'email'])->get(),
        ]);
    }

    public function store(StoreSchedulingData $data): RedirectResponse
    {
        try {
            $this->schedulingService->create($data);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('schedulings')->with('success', 'Solicitação de agendamento encaminhada.');
    }

    public function updateStatus(UpdateSchedulingStatusData $data, Scheduling $scheduling): RedirectResponse
    {
        try {
            $this->schedulingService->updateStatus($scheduling, $data);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status do agendamento atualizado.');
    }
}
