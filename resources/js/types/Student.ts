import Course from '@/types/Course';
import User from '@/types/User';

export default interface Student {
    id: number;
    user_id: number;
    ra: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    user: User;
    courses: Course[];
}
