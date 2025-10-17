<script setup lang="ts">
import IndexLayout from '@/layouts/IndexLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import UserButtons from '@/components/app/users/UserButtons.vue';
import UserList from '@/components/app/users/UserList.vue';
import UserFilters from '@/components/app/users/UserFilters.vue';
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
</script>

<template>
    <Head title="Usuários" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout title="Usuários" description="Gerencie os usuários">

            <template #buttons>
                <UserButtons :roles_list="rolesListPtBr" />
            </template>

            <template #filters>
                <UserFilters :roles_list="rolesListPtBr" />
            </template>

            <template #table>
                <UserList :users_list="usersListPtBr.data" :roles_list="rolesListPtBr" />
            </template>

            <template #pagination>
                <Pagination :pagination="usersListPtBr" />
            </template>

        </IndexLayout>
    </AppLayout>
</template>
