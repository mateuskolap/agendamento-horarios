<?php

namespace App\Services;

use App\Models\Availability;
use RuntimeException;

class AvailabilityService
{
    public function create(array $data): Availability
    {
        $itsOverlapping = $this->itsOverlapping(
            $data['user_id'],
            $data['weekday'],
            $data['start_time'],
            $data['end_time'],
        );

        if ($itsOverlapping) {
            throw new RuntimeException('A disponibilidade informada está sobreposta a outra já existente.');
        }

        return Availability::create($data);
    }

    public function update(Availability $availability, array $data): Availability
    {
        $itsOverlapping = $this->itsOverlapping(
            $data['user_id'],
            $data['weekday'],
            $data['start_time'],
            $data['end_time'],
            $availability
        );

        if ($itsOverlapping) {
            throw new RuntimeException('A disponibilidade informada está sobreposta a outra já existente.');
        }

        $availability->update($data);

        return $availability->refresh();
    }

    private function itsOverlapping(int $userId, int $weekday, string $startTime, string $endTime, ?Availability $exceptOnAvailability = null): bool
    {
        return Availability::query()
            ->whereUserId($userId)
            ->whereWeekday($weekday)
            ->where('start_time', '<=', $endTime)
            ->where('end_time', '>=', $startTime)
            ->when($exceptOnAvailability, function ($query) use ($exceptOnAvailability) {
                $query->whereNot('id', $exceptOnAvailability->id);
            })
            ->exists();
    }
}
