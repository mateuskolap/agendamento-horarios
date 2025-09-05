import Student from '@/types/Student';
import Coordinator from '@/types/Coordinator';

export default interface User {
    id: number;
    email: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    students: Student[];
    coordinators: Coordinator[];
}
