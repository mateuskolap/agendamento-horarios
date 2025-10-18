<?php

namespace App\DTOs;

use Carbon\Carbon;

class StoreAvailabilityDTO
{
    public function __construct(
        public int $user_id,
        public int $weekday,
        public Carbon $start_time,
        public Carbon $end_time,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            weekday: $data['weekday'],
            start_time: Carbon::createFromFormat('H:i', $data['start_time']),
            end_time: Carbon::createFromFormat('H:i', $data['end_time']),
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'weekday' => $this->weekday,
            'start_time' => $this->start_time->format('H:i'),
            'end_time' => $this->end_time->format('H:i'),
        ];
    }
}
