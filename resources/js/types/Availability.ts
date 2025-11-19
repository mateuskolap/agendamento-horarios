import { User } from '@/types/index';

export default interface Availability {
    id: number;
    user_id: number;
    weekday: number;
    start_time: string;
    end_time: string;
    created_at: string;
    updated_at: string;

    user: User;
}
