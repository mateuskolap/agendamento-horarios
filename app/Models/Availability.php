<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'educator_id',
        'educator_type',
        'weekday',
        'time',
    ];

    public function educator(): MorphTo
    {
        return $this->morphTo();
    }

    public function schedulings(): BelongsToMany
    {
        return $this->belongsToMany(Scheduling::class, 'availability_schedulings');
    }
}




