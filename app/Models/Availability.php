<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Availability extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'role_id',
        'weekday',
        'time',
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function schedulings(): BelongsToMany
    {
        return $this->belongsToMany(Scheduling::class);
    }

    public function scopeAccessibleByUser(Builder $query, User $user)
    {
        if ($user->hasRole('admin')) {
            return $query;
        }

        if ($user->hasRole('coordinator')) {
            $users = $user->coordinatedCourses->map->coordinator;
            return $query->whereIn('user_id', $users->pluck('id'));
        }
    }
}
