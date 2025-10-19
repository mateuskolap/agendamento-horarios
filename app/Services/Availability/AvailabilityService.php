<?php

namespace App\Services\Availability;

use App\Data\Availability\StoreAvailabilityData;
use App\Models\Availability;
use RuntimeException;

class AvailabilityService
{
    public function create(StoreAvailabilityData $data): Availability
    {
        if ($this->itsOverlapping($data)) {
            throw new RuntimeException('A disponibilidade informada está sobreposta a outra já existente.');
        }

        if ($existingAvailability = $this->existsByStartOrEndTime($data)) {
            if ($existingAvailability->end_time->equalTo($data->start_time)) {
                $existingAvailability->update([
                    'end_time' => $data->end_time,
                ]);

            } elseif ($existingAvailability->start_time->equalTo($data->end_time)) {
                $existingAvailability->update([
                    'start_time' => $data->start_time,
                ]);
            }

            return $existingAvailability->refresh();
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

    private function existsByStartOrEndTime(StoreAvailabilityData $data): ?Availability
    {
        return Availability::query()
            ->whereUserId($data->user_id)
            ->whereWeekday($data->weekday)
            ->where(function ($query) use ($data) {
                $query->whereTime('end_time', $data->start_time)
                    ->orWhereTime('start_time', $data->end_time);
            })
            ->first();
    }
}
