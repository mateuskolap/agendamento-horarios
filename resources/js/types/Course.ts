import type Organization from '@/types/Organization';
import { User } from '.';

export default interface Course {
    id: number;
    name: string;
    organization_id: number;
    coordinator_id: number;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string | null;

    organization: Organization;
    coordinator: User;
    pivot: any;
}
