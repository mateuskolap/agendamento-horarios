<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

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
     * @return BelongsToMany<Course, User, \Illuminate\Database\Eloquent\Relations\Pivot>
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
     * Agendamentos onde o usuário é o estudante
     *
     * @return HasMany<Scheduling, User>
     */
    public function studentSchedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class, 'student_id');
    }

    /**
     * Agendamentos onde o usuário é o educador (coordenador ou professor)
     *
     * @return HasMany<Scheduling, User>
     */
    public function educatorSchedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class, 'educator_id');
    }
}
