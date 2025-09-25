import { User } from '@/types/index';
import Course from '@/types/Course';

export default interface Coordinator {
    id: number;
    user_id: number;

    user: User;
    courses: Course[];
}
