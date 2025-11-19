<?php

namespace App\Services\CourseUser;

use App\Data\CourseUser\StoreCourseUserData;
use App\Models\User;
use RuntimeException;
use Spatie\Permission\Models\Role;

class CourseUserService
{
    /**
     * @throws RuntimeException
     */
    public function create(StoreCourseUserData $data, User $user): void
    {
        $role = Role::find($data->role_id);
        if (!$role->courses_editable) {
            throw new RuntimeException('Não é permitida a alteração dos cursos para usuários com a função: ' . $role->name);
        }

        foreach ($data->course_ids as $courseId) {
            if ($user->courses()->wherePivot('role_id', $data->role_id)->where('courses.id', $courseId)->exists()) {
                continue; // Esse curso já está vinculado ao usuário
            }
            $user->courses()->attach($courseId, ['role_id' => $data->role_id]);
        }
    }
}
