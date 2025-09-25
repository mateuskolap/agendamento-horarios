import Course from '@/types/Course';

export default interface Organization {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    courses: Course[];
}
