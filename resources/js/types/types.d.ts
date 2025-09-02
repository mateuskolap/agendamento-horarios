declare namespace App.Models {
    export type Course = {
        id: number;
        name: string;
        description: string | null;
        created_at: string | null;
        updated_at: string | null;
        deleted_at: string | null;
    };
    export type CourseStudent = {
        student_id: number;
        course_id: number;
        created_at: string | null;
        updated_at: string | null;
        deleted_at: string | null;
    };
    export type Student = {
        id: number;
        user_id: number;
        ra: string;
        created_at: string | null;
        updated_at: string | null;
        deleted_at: string | null;
    };
    export type User = {
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        created_at: string | null;
        updated_at: string | null;
        deleted_at: string | null;
    };
}
