<script setup lang="ts">
import { NButton, NDataTable, NIcon } from 'naive-ui';
import users from '@/routes/users';
import type { User } from '@/types';
import Role from '@/types/Role';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { h } from 'vue';
import { Pencil, Trash } from '@vicons/ionicons5';
import MobileCardList from '@/components/ui/mobile-card/MobileCardList.vue';
import UserField from './UserField.vue';

defineProps<{
    users_list: User[];
}>();

const { confirmDelete } = useConfirmDelete();

const emit = defineEmits<{
    (e: 'open-modal', user?: User): void;
}>();

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
                        onClick: () => emit('open-modal', row),
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
</script>

<template>
    <div class="hidden md:block">
        <NDataTable :columns="columns" :data="users_list" size="small" />
    </div>

    <div class="md:hidden">
        <MobileCardList :items="users_list" class="space-y-3" item-key="id">
            <template #header-left="{ item: user }">
                <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ user.id }}. {{ user.name }}</p>
            </template>

            <template #header-right="{ item: user }">
                <NButton size="small" tertiary @click="emit('open-modal', user)">
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
</template>

<style scoped></style>
