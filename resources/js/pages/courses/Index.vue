<script lang="ts" setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import CourseButtons from '@/components/app/courses/CourseButtons.vue';
import CourseFilters from '@/components/app/courses/CourseFilters.vue';
import CourseList from '@/components/app/courses/CourseList.vue';
import courses from '@/routes/courses';
import type { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import PaginatedData from '@/types/PaginatedData';
import { Head } from '@inertiajs/vue3';

defineProps<{
    courses_list: PaginatedData<Course>;
    coordinators_list: User[];
    organizations_list: Organization[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cursos',
        href: courses.index().url,
    },
];
</script>

<template>
    <Head title="Cursos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout description="Gerencie os cursos" title="Cursos">
            <template #buttons>
                <CourseButtons :coordinators_list="coordinators_list" :organizations_list="organizations_list" />
            </template>

            <template #filters>
                <CourseFilters :coordinators_list="coordinators_list" :organizations_list="organizations_list" />
            </template>

            <template #table>
                <CourseList :courses_list="courses_list.data" :organizations_list="organizations_list" :coordinators_list="coordinators_list" />
            </template>

            <template #pagination>
                <Pagination :pagination="courses_list" />
            </template>
        </IndexLayout>
    </AppLayout>
</template>
