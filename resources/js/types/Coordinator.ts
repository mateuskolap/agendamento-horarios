import Course from '@/types/Course';

export default interface Coordinator {
    id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    courses: Course[];
}
