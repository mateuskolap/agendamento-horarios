<script setup lang="ts">
import PageLayout from '@/components/PageLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import courses from '@/routes/courses';
import type { BreadcrumbItem } from '@/types';
import Course from '@/types/Course';
import PaginatedData from '@/types/PaginatedData';
import { Head, router } from '@inertiajs/vue3';
import { Pencil, Trash, Add } from '@vicons/ionicons5';
import { NButton, NDataTable, NIcon, NPagination } from 'naive-ui';
import { h, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';

defineProps<{
    courses_list: PaginatedData<Course>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cursos',
        href: courses.index().url,
    },
];

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
</script>

<template>
    <Head title="Cursos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Cursos" description="Gerencie os cursos">
            <template #buttons>
                <Button variant="outline" class="hover:bg-green-500 hover:dark:bg-green-700 cursor-pointer">
                    <Add/>
                    Adicionar
                </Button>
            </template>
            <template #table>
                <NDataTable :columns="columns" :data="courses_list.data"/>
                <div class="flex justify-end">
                    <NPagination v-model:page="currentPage" :page-count="courses_list.last_page"/>
                </div>
            </template>
        </PageLayout>
    </AppLayout>
</template>
