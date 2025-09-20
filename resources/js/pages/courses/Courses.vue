<script lang="ts" setup>
import PageLayout from '@/components/PageLayout.vue';
import { Button } from '@/components/ui/button';
import MobileCardList from '@/components/ui/mobile-card/MobileCardList.vue';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import CourseField from '@/pages/courses/components/CourseField.vue';
import CourseFormModal from '@/pages/courses/modals/CourseFormModal.vue';
import courses from '@/routes/courses';
import type { BreadcrumbItem } from '@/types';
import Coordinator from '@/types/Coordinator';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import PaginatedData from '@/types/PaginatedData';
import { Head, router } from '@inertiajs/vue3';
import { Add, Pencil, Trash } from '@vicons/ionicons5';
import { NButton, NDataTable, NIcon, NPagination } from 'naive-ui';
import Swal from 'sweetalert2';
import { h, ref, watch } from 'vue';

const props = defineProps<{
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
    { title: 'Organização', key: 'organization.name' },
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
    organizations_list: Organization[];
}>({
    course: null,
    mode: 'create',
    coordinators_list: props.coordinators_list,
    organizations_list: props.organizations_list,
});

function openModal(course: Course | null) {
    if (course) {
        modal.open({
            course: course,
            mode: 'edit',
            coordinators_list: props.coordinators_list,
            organizations_list: props.organizations_list,
        });

        return;
    }

    modal.open({
        course: null,
        mode: 'create',
        coordinators_list: props.coordinators_list,
        organizations_list: props.organizations_list,
    });
}
</script>

<template>
    <Head title="Cursos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout description="Gerencie os cursos" title="Cursos">
            <template #buttons>
                <Button class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" variant="outline" @click="openModal(null)">
                    <Add />
                    Adicionar
                </Button>
            </template>
            <template #table>
                <div class="hidden md:block">
                    <NDataTable :columns="columns" :data="courses_list.data" size="small" />
                    <div class="mt-4 flex justify-end">
                        <NPagination v-model:page="currentPage" :page-count="courses_list.last_page" />
                    </div>
                </div>

                <div class="md:hidden">
                    <MobileCardList :items="courses_list.data" class="space-y-3" item-key="id">
                        <template #header-left="{ item: course }">
                            <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ course.id }}. {{ course.name }}</p>
                        </template>

                        <template #header-right="{ item: course }">
                            <NButton size="small" tertiary @click="openModal(course)">
                                <template #icon>
                                    <NIcon><Pencil /></NIcon>
                                </template>
                            </NButton>
                            <NButton size="small" tertiary @click="confirmDelete(course)">
                                <template #icon>
                                    <NIcon class="text-red-500 dark:text-red-700"><Trash /></NIcon>
                                </template>
                            </NButton>
                        </template>

                        <template #default="{ item: course }">
                            <CourseField :value="course.organization?.name" label="Organização" />
                            <CourseField :value="course.coordinator?.user?.name" label="Coordenador" />
                        </template>
                    </MobileCardList>

                    <div class="mt-4 flex justify-center">
                        <NPagination v-model:page="currentPage" :page-count="courses_list.last_page" />
                    </div>
                </div>
            </template>
        </PageLayout>

        <CourseFormModal v-bind="modal.modalBindInfo()" @update:is-open="modal.setOpen($event)" />
    </AppLayout>
</template>
