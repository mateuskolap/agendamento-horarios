<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class Scheduling extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'student_id',
        'educator_id',
        'educator_role_id',
        'course_id',
        'date',
        'time',
        'subject',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educatorRole(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
