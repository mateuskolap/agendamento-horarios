<script setup lang="ts">
import IndexLayout from '@/layouts/IndexLayout.vue';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import UserButtons from '@/components/app/users/UserButtons.vue';
import UserList from '@/components/app/users/UserList.vue';
import UserFilters from '@/components/app/users/UserFilters.vue';
import FormModal from '@/pages/users/modals/FormModal.vue';
import users from '@/routes/users';
import type { BreadcrumbItem, User } from '@/types';
import PaginatedData from '@/types/PaginatedData';
import Role from '@/types/Role';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { computed } from 'vue';
import { translateArray } from '@/plugins/translate';

const props = defineProps<{
    users_list: PaginatedData<User>;
    roles_list: Role[];
}>();

const rolesListPtBr = computed(() => translateArray(props.roles_list, ['name']));

const usersListPtBr = computed(() => ({
    ...props.users_list,
    data: props.users_list.data.map(user => ({
        ...user,
        roles: translateArray(user.roles, ['name'])
    }))
}));

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
        <IndexLayout title="Usuários" description="Gerencie os usuários">

            <template #buttons>
                <UserButtons @open-modal="openModal()" />
            </template>

            <template #filters>
                <UserFilters :roles_list="rolesListPtBr" />
            </template>

            <template #table>
                <UserList :users_list="usersListPtBr.data" @open-modal="openModal" />
            </template>

            <template #pagination>
                <Pagination :pagination="usersListPtBr" />
            </template>

        </IndexLayout>

        <FormModal v-bind="modal.modalBindInfo()" :roles_list="rolesListPtBr" @update:is-open="closeModal" />
    </AppLayout>
</template>
