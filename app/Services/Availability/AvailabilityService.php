<?php

namespace App\Services\Availability;

use App\Data\Availability\StoreAvailabilityData;
use App\Data\AvailableTimesForUserData;
use App\Enums\SchedulingStatusEnum;
use App\Models\Availability;
use App\Models\Scheduling;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RuntimeException;

class AvailabilityService
{
    public function create(StoreAvailabilityData $data): Availability
    {
        if ($this->itsOverlapping($data)) {
            throw new RuntimeException('A disponibilidade informada está sobreposta a outra já existente.');
        }

        $adjacents = $this->findAdjacentAvailabilities($data);

        if ($adjacents->isNotEmpty()) {
            $minStart = $adjacents->min('start_time');
            $maxEnd = $adjacents->max('end_time');

            $minStart = min($minStart, $data->start_time);
            $maxEnd = max($maxEnd, $data->end_time);

            Availability::whereIn('id', $adjacents->pluck('id'))->delete();

            return Availability::create([
                ...$data->toArray(),
                'start_time' => $minStart,
                'end_time' => $maxEnd,
            ]);
        }

        return Availability::create($data->toArray());
    }

    public function getAvailableTimesForRequestedOnDay(Carbon $date, User $requested): Collection
    {
        $busySchedules = Scheduling::whereRequestedId($requested->id)
            ->whereDate('date', $date)
            ->where('status', '!=', SchedulingStatusEnum::REJECTED)
            ->get(['start_time', 'end_time'])
            ->map(function (Scheduling $s) use ($date) {
                return [
                    'start' => $date->copy()->setTimeFrom($s->start_time),
                    'end' => $date->copy()->setTimeFrom($s->end_time),
                ];
            });

        $availableTimes = $requested->availabilities()
            ->where('weekday', $date->dayOfWeekIso)
            ->get()
            ->flatMap(function ($availability) use ($date) {
                $start = $date->copy()->setTimeFromTimeString($availability->start_time);
                $end = $date->copy()->setTimeFromTimeString($availability->end_time);

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

    private function itsOverlapping(StoreAvailabilityData $data): bool
    {
        return Availability::query()
            ->whereUserId($data->user_id)
            ->whereWeekday($data->weekday)
            ->whereTime('start_time', '<', $data->end_time)
            ->whereTime('end_time', '>', $data->start_time)
            ->exists();
    }

    private function findAdjacentAvailabilities(StoreAvailabilityData $data): Collection
    {
        return Availability::query()
            ->whereUserId($data->user_id)
            ->whereWeekday($data->weekday)
            ->where(function ($query) use ($data) {
                $query->whereTime('end_time', $data->start_time)
                    ->orWhereTime('start_time', $data->end_time);
            })
            ->get();
    }
}
