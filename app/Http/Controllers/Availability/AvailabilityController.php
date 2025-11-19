<?php

namespace App\Http\Controllers\Availability;

use App\Data\Availability\AvailableTimesForUserData;
use App\Data\Availability\IndexAvailabilityData;
use App\Data\Availability\StoreAvailabilityData;
use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\User;
use App\Services\Availability\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
    )
    {
    }

    public function index(IndexAvailabilityData $data): Response
    {
        return Inertia::render('settings/Availabilities', [
            'availabilities_list' => Availability::query()
                ->whereWeekday($data->weekday)
                ->orderBy('start_time')
                ->get(),
        ]);
    }

    public function availableTimesForRequestedOnDay(AvailableTimesForUserData $data, User $requested)
    {
        $times = $this->availabilityService->getAvailableTimesForRequestedOnDay($data->date, $requested);
        
        return response()->json($times->values()->toArray());
    }

    public function store(StoreAvailabilityData $data): RedirectResponse
    {
        try {
            $this->availabilityService->create($data);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Disponibilidade adicionada com sucesso.');
    }

    public function destroy(Availability $availability): RedirectResponse
    {
        $availability->delete();

        return back()->with('success', 'Disponibilidade removida com sucesso.');
    }
}
