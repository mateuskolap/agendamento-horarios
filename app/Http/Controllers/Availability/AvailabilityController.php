<?php

namespace App\Http\Controllers\Availability;

use App\Data\Availability\IndexAvailabilityData;
use App\Data\Availability\StoreAvailabilityData;
use App\Data\AvailableTimesForUserData;
use App\Enums\SchedulingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Scheduling;
use App\Models\User;
use App\Services\Availability\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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
        $busySchedules = Scheduling::whereRequestedId($requested->id)
            ->whereDate('date', $data->date)
            ->where('status', '!=', SchedulingStatusEnum::REJECTED)
            ->get(['start_time', 'end_time'])
            ->map(function (Scheduling $s) use ($data) {
                return [
                    'start' => $data->date->copy()->setTimeFrom($s->start_time),
                    'end' => $data->date->copy()->setTimeFrom($s->end_time),
                ];
            });

        $availableTimes = $requested->availabilities()
            ->where('weekday', $data->date->dayOfWeekIso)
            ->get()
            ->flatMap(function ($availability) use ($data) {
                $start = $data->date->copy()->setTimeFromTimeString($availability->start_time);
                $end = $data->date->copy()->setTimeFromTimeString($availability->end_time);

                $slots = collect();

                while ($start->lt($end)) {
                    $slots->push($start);
                    $start->addMinutes(30);
                }

                return $slots;
            });

        return $availableTimes->filter(function (Carbon $slot) use ($busySchedules) {
            return !$busySchedules->contains(function ($busy) use ($slot) {
                return $slot->gte($busy['start']) && $slot->lt($busy['end']);
            });
        })->map(fn(Carbon $t) => $t->toTimeString())->values();
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
