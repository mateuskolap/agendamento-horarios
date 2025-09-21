<script setup lang="ts">
import PageLayout from '@/components/PageLayout.vue';
import { Button } from '@/components/ui/button';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import AppLayout from '@/layouts/AppLayout.vue';
import users from '@/routes/users';
import type { BreadcrumbItem, User } from '@/types';
import PaginatedData from '@/types/PaginatedData';
import { Head } from '@inertiajs/vue3';
import { Add, Pencil, Trash } from '@vicons/ionicons5';
import { NButton, NIcon, NPagination } from 'naive-ui';
import { h } from 'vue';
import { usePagination } from '@/composables/usePagination';
import UsersFilters from '@/pages/users/components/UsersFilters.vue';
import Role from '@/types/Role';

defineProps<{
    users_list: PaginatedData<User>;
    roles_list: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
];

const { confirmDelete } = useConfirmDelete();

const columns = [
    { title: 'ID', key: 'id' },
    { title: 'Nome', key: 'name' },
    {
        title: 'Papéis',
        key: 'roles',
        render(row: User) {
            return row.roles.map((r) => r.name).join(', ');
        },
    },
    {
        title: 'Ações',
        key: 'actions',
        render(row: User) {
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
                        onClick: () => confirmDelete(users.destroy(row.id).url),
                    },
                    {
                        icon: () => h(NIcon, { class: 'text-red-500 dark:text-red-700' }, { default: () => h(Trash) }),
                    },
                ),
            ]);
        },
    },
];

const { currentPage } = usePagination({
    route: users.index().url,
    params: {},
});
</script>

<template>
    <Head title="Usuários" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Usuários" description="Gerencie os usuários">

            <template #buttons>
                <Button class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" variant="outline">
                    <Add />
                    Adicionar
                </Button>
            </template>

            <template #filters>
                <UsersFilters :roles_list="roles_list"/>
            </template>

            <template #table>
                <NDataTable :columns="columns" :data="users_list.data" />
                <div class="mt-4 flex justify-end">
                    <NPagination v-model:page="currentPage" :page-count="users_list.last_page" />
                </div>
            </template>

        </PageLayout>
    </AppLayout>
</template>
