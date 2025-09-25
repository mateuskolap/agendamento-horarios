<script setup lang="ts">
import PageLayout from '@/components/PageLayout.vue';
import { Button } from '@/components/ui/button';
import MobileCardList from '@/components/ui/mobile-card/MobileCardList.vue';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import UserField from '@/pages/users/components/UserField.vue';
import UserFormModal from '@/pages/users/modals/UserFormModal.vue';
import UsersFilters from '@/pages/users/components/UsersFilters.vue';
import users from '@/routes/users';
import type { BreadcrumbItem, User } from '@/types';
import PaginatedData from '@/types/PaginatedData';
import Role from '@/types/Role';
import { Head } from '@inertiajs/vue3';
import { Add, Pencil, Trash } from '@vicons/ionicons5';
import { NButton, NDataTable, NIcon, NPagination } from 'naive-ui';
import { h } from 'vue';
import { usePagination } from '@/composables/usePagination';

const props = defineProps<{
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

const modal = useModal<{
    user: User | null;
    mode: 'create' | 'edit';
    roles_list: Role[];
}>({
    user: null,
    mode: 'create',
    roles_list: props.roles_list,
});

function openModal(user: User | null) {
    if (user) {
        modal.open({
            user: user,
            mode: 'edit',
            roles_list: props.roles_list,
        });
        return;
    }

    modal.open({
        user: null,
        mode: 'create',
        roles_list: props.roles_list,
    });
}

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
                <Button class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" variant="outline" @click="openModal(null)">
                    <Add />
                    Adicionar
                </Button>
            </template>

            <template #filters>
                <UsersFilters :roles_list="roles_list"/>
            </template>

            <template #table>
                <div class="hidden md:block">
                    <NDataTable :columns="columns" :data="users_list.data" size="small" />
                </div>

                <div class="md:hidden">
                    <MobileCardList :items="users_list.data" class="space-y-3" item-key="id">
                        <template #header-left="{ item: user }">
                            <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ user.id }}. {{ user.name }}</p>
                        </template>

                        <template #header-right="{ item: user }">
                            <NButton size="small" tertiary @click="openModal(user)">
                                <template #icon>
                                    <NIcon><Pencil /></NIcon>
                                </template>
                            </NButton>
                            <NButton size="small" tertiary @click="confirmDelete(users.destroy(user.id).url)">
                                <template #icon>
                                    <NIcon class="text-red-500 dark:text-red-700"><Trash /></NIcon>
                                </template>
                            </NButton>
                        </template>

                        <template #default="{ item: user }">
                            <UserField :value="user.roles.map((r: Role) => r.name).join(', ')" label="Papéis" />
                        </template>
                    </MobileCardList>
                </div>

                <div class="mt-4 flex justify-end">
                    <NPagination v-model:page="currentPage" :page-count="users_list.last_page" />
                </div>
            </template>

        </PageLayout>

        <UserFormModal
            v-model:is-open="modal.isOpen.value"
            :user="modal.getParams()?.user || null"
            :mode="modal.getParams()?.mode || 'create'"
            :roles_list="modal.getParams()?.roles_list || []"
        />
    </AppLayout>
</template>
