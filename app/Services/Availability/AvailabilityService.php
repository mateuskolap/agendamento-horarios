<?php

namespace App\Services\Availability;

use App\Data\Availability\StoreAvailabilityData;
use App\Models\Availability;
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
