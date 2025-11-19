<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case COORDINATOR = 'coordinator';
    case PROFESSOR = 'professor';
    case STUDENT = 'student';
}
