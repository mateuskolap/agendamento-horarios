<script setup lang="ts">
import PageLayout from '@/components/PageLayout.vue';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import UserButtons from '@/pages/users/components/UserButtons.vue';
import UserList from '@/pages/users/components/UserList.vue';
import UsersFilters from '@/pages/users/components/UsersFilters.vue';
import UserFormModal from '@/pages/users/modals/UserFormModal.vue';
import users from '@/routes/users';
import type { BreadcrumbItem, User } from '@/types';
import PaginatedData from '@/types/PaginatedData';
import Role from '@/types/Role';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/components/ui/pagination/Pagination.vue';

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

const modal = useModal<{
    user?: User;
}>();

function openModal(user?: User) {
    modal.open({ user: user });
}

function closeModal() {
    modal.close();
}
</script>

<template>
    <Head title="Usuários" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Usuários" description="Gerencie os usuários">

            <template #buttons>
                <UserButtons @open-modal="openModal()" />
            </template>

            <template #filters>
                <UsersFilters :roles_list="roles_list" />
            </template>

            <template #table>
                <UserList :users_list="users_list.data" @open-modal="openModal" />
            </template>

            <template #pagination>
                <Pagination :pagination="users_list" />
            </template>

        </PageLayout>

        <UserFormModal v-bind="modal.modalBindInfo()" :roles_list="roles_list" @update:is-open="closeModal" />
    </AppLayout>
</template>
