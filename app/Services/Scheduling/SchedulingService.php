<?php

namespace App\Services\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Enums\SchedulingStatusEnum;
use App\Models\Scheduling;
use App\Models\User;
use RuntimeException;

class SchedulingService
{
    public function create(StoreSchedulingData $data): Scheduling
    {
        $requested = User::find($data->requested_id);

        if (!$this->requestedIsAvailableForSchedule($data)) {
            throw new RuntimeException("{$requested->name} não está disponível para o horário solicitado.");
        }

        return Scheduling::create([
            'requester_id' => \Auth::id(),
            'status' => SchedulingStatusEnum::PENDING,
            ...$data->toArray(),
        ]);
    }

    private function requestedIsAvailableForSchedule(StoreSchedulingData $data): bool
    {
        return !Scheduling::query()
            ->whereRequestedId($data->requested_id)
            ->whereDate('date', $data->date)
            ->whereTime('start_time', '<', $data->end_time)
            ->whereTime('end_time', '>', $data->start_time)
            ->where('status', '!=', SchedulingStatusEnum::REJECTED)
            ->exists();
    }
}
