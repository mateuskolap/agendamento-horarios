<?php

namespace App\Services\Availability;

use App\DTOs\StoreAvailabilityDTO;
use App\Models\Availability;
use RuntimeException;

class AvailabilityService
{
    public function create(StoreAvailabilityDTO $data): Availability
    {
        if ($this->itsOverlapping($data)) {
            throw new RuntimeException('A disponibilidade informada está sobreposta a outra já existente.');
        }

        $existingAvailability = $this->existsByStartOrEndTime($data);
        if ($existingAvailability) {
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

    private function itsOverlapping(StoreAvailabilityDTO $data): bool
    {
        return Availability::query()
            ->whereUserId($data->user_id)
            ->whereWeekday($data->weekday)
            ->where('start_time', '<', $data->end_time)
            ->where('end_time', '>', $data->start_time)
            ->exists();
    }

    private function existsByStartOrEndTime(StoreAvailabilityDTO $data): ?Availability
    {
        return Availability::query()
            ->whereUserId($data->user_id)
            ->whereWeekday($data->weekday)
            ->where(function ($query) use ($data) {
                $query->whereEndTime($data->start_time)
                    ->orWhere('start_time', $data->end_time);
            })
            ->first();
    }
}
