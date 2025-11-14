<?php

namespace App\Http\Controllers\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Data\Scheduling\UpdateSchedulingStatusData;
use App\Enums\SchedulingStatusEnum;
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
        return Inertia::render('schedulings/Index', [
            'requested_schedulings' => Scheduling::where('requester_id', \Auth::user()->id),
            'received_schedulings' => Scheduling::where('requested_id', \Auth::user()->id),
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
        if ($scheduling->status !== SchedulingStatusEnum::PENDING) {
            return back()->with('error', 'Apenas agendamentos pendentes podem ser atualizados.');
        }

        if ($data->status === SchedulingStatusEnum::REJECTED) {
            if (!$data->rejection_reason) {
                return back()->with('error', 'Para rejeitar um agendamento você deve informar o motivo da rejeição.');
            }

            \App\Models\Response::create([
                'scheduling_id' => $scheduling->id,
                'message' => $data->rejection_reason,
            ]);
        }

        $scheduling->update([
            'status' => $data->status,
        ]);

        return back()->with('success', 'Status do agendamento atualizado.');
    }
}
