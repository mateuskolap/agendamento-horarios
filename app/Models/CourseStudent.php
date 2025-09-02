<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseStudent extends Pivot
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_id'
    ];
}
