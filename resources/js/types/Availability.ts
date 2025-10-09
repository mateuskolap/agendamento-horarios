import { User } from '@/types/index';
import Role from '@/types/Role';

export default interface Availability {
    id: number;
    user_id: number;
    role_id: number;
    weekday: number;
    time: string;

    user: User;
    role: Role;
}
