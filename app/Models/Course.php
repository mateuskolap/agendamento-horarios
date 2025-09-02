<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

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
