<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Attribute;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Cursos coordenados pelo usuário (quando possui role 'coordinator')
     *
     * @return HasMany<Course, User>
     */
    public function coordinatedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'coordinator_id');
    }

    /**
     * Cursos vinculados ao usuário
     *
     * @return BelongsToMany<Course, User, Pivot>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Agendamentos onde o usuário é o solicitante
     *
     * @return HasMany<Scheduling, User>
     */
    public function requesterSchedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class, 'requester_id');
    }

    /**
     * Agendamentos onde o usuário é o solicitado
     *
     * @return HasMany<Scheduling, User>
     */
    public function requestedSchedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class, 'requested_id');
    }

    public function isAdmin(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->hasRole('admin');
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->logExcept(['password']);
    }

    #[Scope]
    public function availableForSchedulingsWith(Builder $query, User $user): Builder
    {
        return $query->whereRelation('roles', 'can_receive_scheduling_requests', true)
            ->whereHas('courses', function ($courseQuery) use ($user) {
                $courseQuery->whereIn('courses.id', $user->courses()->pluck('id'));
            });
    }
}
