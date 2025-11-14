<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'organization_id',
        'coordinator_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function schedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    #[Scope]
    protected function availableByRequested(Builder $query, User $requested): void
    {
        $query->where(function ($query) use ($requested) {
            $query
                ->where('coordinator_id', $requested->id)
                ->orWhereRelation('users', 'users.id', $requested->id);
        });
    }
}
