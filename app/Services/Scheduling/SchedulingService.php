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
        if (!$this->educatorIsAvailableForSchedule($data)) {
            throw new RuntimeException('O educador não está disponível para o horário solicitado.');
        }

        return Scheduling::create([
            ...$data->toArray(),
            'status' => SchedulingStatusEnum::PENDING,
        ]);
    }

    private function educatorIsAvailableForSchedule(StoreSchedulingData $data): bool
    {
        return !Scheduling::query()
            ->whereEducatorId($data->educator_id)
            ->whereDate('date', $data->date)
            ->whereTime('start_time', '<', $data->end_time)
            ->whereTime('end_time', '>', $data->start_time)
            ->where('status', '!=', SchedulingStatusEnum::REJECTED)
            ->exists();
    }
}
