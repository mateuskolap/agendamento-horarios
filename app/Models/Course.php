<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }

    public function scopeOfUser(Builder $query)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $query;
        }

        if ($user->hasRole('coordinator') && $user->coordinator) {
            return $query->where('coordinator_id', $user->coordinator->id);
        }

        if ($user->hasRole('student') && $user->students()->exists()) {
            $studentIds = $user->students->pluck('id');
            if ($studentIds->isNotEmpty()) {
                return $query->whereHas('students', fn($q) => $q->whereIn('students.id', $studentIds));
            }
        }

        return $query->whereRaw('0 = 1');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'course_student',
            'course_id',
            'student_id'
        )
            ->using(CourseStudent::class)
            ->withPivot('id', 'deleted_at')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }
}
