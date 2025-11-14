<?php

namespace App\Http\Controllers\Scheduling;

use App\Data\Scheduling\CreateSchedulingData;
use App\Data\Scheduling\StoreSchedulingData;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Scheduling\SchedulingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

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
}
