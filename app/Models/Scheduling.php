<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Scheduling extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'educator_id',
        'educator_role_id',
        'course_id',
        'subject',
        'status',
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

    public function availabilities(): BelongsToMany
    {
        return $this->belongsToMany(Availability::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
