import Coordinator from '@/types/Coordinator';
import Student from '@/types/Student';

export default interface Course {
    id: number;
    name: string;
    description: string;
    coordinator_id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    coordinator: Coordinator;
    students: Student[];
}
