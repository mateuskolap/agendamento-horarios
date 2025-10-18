<?php

namespace App\Models;

use App\Models\Scopes\AvailabilityAccessibleByUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([AvailabilityAccessibleByUserScope::class])]
class Availability extends Model
{
    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
