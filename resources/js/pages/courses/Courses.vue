<script setup lang="ts">
import PageLayout from '@/components/PageLayout.vue';
import { Button } from '@/components/ui/button';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import CourseFormModal from '@/pages/courses/modals/CourseFormModal.vue';
import courses from '@/routes/courses';
import type { BreadcrumbItem } from '@/types';
import Coordinator from '@/types/Coordinator';
import Course from '@/types/Course';
import PaginatedData from '@/types/PaginatedData';
import { Head, router } from '@inertiajs/vue3';
import { Add, Pencil, Trash } from '@vicons/ionicons5';
import { NButton, NDataTable, NIcon, NPagination } from 'naive-ui';
import { h, ref, watch } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    courses_list: PaginatedData<Course>;
    coordinators_list: Coordinator[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cursos',
        href: courses.index().url,
    },
];

async function confirmDelete(course: Course) {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação não pode ser desfeita!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
    });

    if (result.isConfirmed) {
        router.delete(courses.destroy(course.id), {
            onSuccess: async () => {
                await Swal.fire({
                    title: 'Excluído!',
                    text: 'O registro foi excluído com sucesso.',
                    icon: 'success',
                });
            },
        });
    }
}

const columns = [
    { title: 'ID', key: 'id' },
    { title: 'Name', key: 'name' },
    { title: 'Coordenador', key: 'coordinator.user.name' },
    {
        title: 'Ações',
        key: 'actions',
        render(row: Course) {
            return h('div', { class: 'flex gap-2' }, [
                h(
                    NButton,
                    {
                        tertiary: true,
                        size: 'small',
                        onClick: () => openModal(row),
                    },
                    {
                        icon: () => h(NIcon, null, { default: () => h(Pencil) }),
                    },
                ),
                h(
                    NButton,
                    {
                        tertiary: true,
                        size: 'small',
                        onClick: () => confirmDelete(row),
                    },
                    {
                        icon: () => h(NIcon, { class: 'text-red-500 dark:text-red-700' }, { default: () => h(Trash) }),
                    },
                ),
            ]);
        },
    },
];

const currentPage = ref(1);

watch(currentPage, (newPage) => {
    router.get(
        courses.index().url,
        { page: newPage },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
});

const modal = useModal<{
    course: Course | null;
    mode: 'create' | 'edit';
    coordinators_list: Coordinator[];
}>({
    course: null,
    mode: 'create',
    coordinators_list: props.coordinators_list,
});

function openModal(course: Course | null) {
    if (course) {
        modal.open({
            course: course,
            mode: 'edit',
            coordinators_list: props.coordinators_list,
        });

        return;
    }

    modal.open({
        course: null,
        mode: 'create',
        coordinators_list: props.coordinators_list,
    });
}
</script>

<template>
    <Head title="Cursos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Cursos" description="Gerencie os cursos">
            <template #buttons>
                <Button variant="outline" class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" @click="openModal(null)">
                    <Add />
                    Adicionar
                </Button>
            </template>
            <template #table>
                <NDataTable :columns="columns" :data="courses_list.data" />
                <div class="flex justify-end">
                    <NPagination v-model:page="currentPage" :page-count="courses_list.last_page" />
                </div>
            </template>
        </PageLayout>

        <CourseFormModal v-bind="modal.modalBindInfo()" @update:is-open="modal.setOpen($event)" />
    </AppLayout>
</template>
