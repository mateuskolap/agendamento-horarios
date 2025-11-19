<?php

namespace App\Services\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Data\Scheduling\UpdateSchedulingStatusData;
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

    public function updateStatus(Scheduling $scheduling, UpdateSchedulingStatusData $data): Scheduling
    {
        if ($scheduling->status !== SchedulingStatusEnum::PENDING) {
            throw new RuntimeException('Apenas agendamentos pendentes podem ser atualizados.');
        }

        if ($this->newStatusNeedsResponse($data->status)) {
            if (!$data->rejection_reason) {
                throw new RuntimeException('Para rejeitar um agendamento você deve informar o motivo da rejeição.');
            }
            $this->createResponse($scheduling, $data->rejection_reason);
        }

        $scheduling->update([
            'status' => $data->status,
        ]);

        return $scheduling->refresh();
    }

    private function createResponse(Scheduling $scheduling, string $response): void
    {
        $scheduling->responses()->create([
            'message' => $response,
        ]);
    }

    private function newStatusNeedsResponse(SchedulingStatusEnum $status): bool
    {
        if ($status === SchedulingStatusEnum::REJECTED) {
            return true;
        }
        return false;
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
