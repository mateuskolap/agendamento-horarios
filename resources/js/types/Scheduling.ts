import type { User } from './index.d';
import type Course from './Course';

export enum SchedulingStatus {
    PENDING = 'pending',
    APPROVED = 'approved',
    REJECTED = 'rejected',
    CANCELLED = 'cancelled',
}

export default interface Scheduling {
    id: number;
    requester_id: number;
    requested_id: number;
    course_id: number;
    date: string;
    start_time: string;
    end_time: string;
    subject: string;
    status: SchedulingStatus;
    created_at: string;
    updated_at: string;

    // Relacionamentos
    requester?: User;
    requested?: User;
    course?: Course;
}

