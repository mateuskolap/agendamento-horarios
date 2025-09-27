<script lang="ts" setup>
import PageLayout from '@/components/PageLayout.vue';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import CourseFilters from '@/pages/courses/components/CourseFilters.vue';
import CourseFormModal from '@/pages/courses/modals/CourseFormModal.vue';
import courses from '@/routes/courses';
import type { BreadcrumbItem } from '@/types';
import Coordinator from '@/types/Coordinator';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import PaginatedData from '@/types/PaginatedData';
import { Head } from '@inertiajs/vue3';
import CourseList from '@/pages/courses/components/CourseList.vue';
import CourseButtons from '@/pages/courses/components/CourseButtons.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';

defineProps<{
    courses_list: PaginatedData<Course>;
    coordinators_list: Coordinator[];
    organizations_list: Organization[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cursos',
        href: courses.index().url,
    },
];

const modal = useModal<{
    course?: Course;
}>();

function openModal(course?: Course) {
    modal.open({ course: course });
}

function closeModal() {
    modal.close();
}
</script>

<template>
    <Head title="Cursos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout description="Gerencie os cursos" title="Cursos">

            <template #buttons>
                <CourseButtons @open-modal="openModal()" />
            </template>

            <template #filters>
                <CourseFilters :coordinators_list="coordinators_list" :organizations_list="organizations_list" />
            </template>

            <template #table>
                <CourseList :courses_list="courses_list.data" @open-modal="openModal"/>
            </template>

            <template #pagination>
                <Pagination :pagination="courses_list" />
            </template>

        </PageLayout>

        <CourseFormModal v-bind="modal.modalBindInfo()" :coordinators_list="coordinators_list" :organizations_list="organizations_list" @update:is-open="closeModal" />
    </AppLayout>
</template>
