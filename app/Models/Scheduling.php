<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheduling extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'educator_id',
        'educator_type',
        'course_id',
        'subject',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function educator(): MorphTo
    {
        return $this->morphTo();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function availabilities(): BelongsToMany
    {
        return $this->belongsToMany(Availability::class, 'availability_schedulings');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
