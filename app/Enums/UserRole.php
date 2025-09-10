<?php

namespace App\Enums;

enum UserRole: string
{
    case PROFESSOR = 'PROFESSOR';
    case STUDENT = 'STUDENT';
    case COORDINATOR = 'COORDINATOR';
}
