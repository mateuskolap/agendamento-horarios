<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemHeader, ItemTitle } from '@/components/ui/item';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { usePermission } from '@/composables/usePermissions';
import users from '@/routes/users';
import type { User } from '@/types';
import { router } from '@inertiajs/vue3';
import { Trash, Eye } from '@vicons/ionicons5';
import UpdateUserDialog from '@/components/app/users/UpdateUserDialog.vue';
import Role from '@/types/Role';

defineProps<{
    users_list: User[];
    roles_list: Role[];
}>();

const { confirmDelete, showDeleteSuccess } = useConfirmDelete();
const canEdit = usePermission()('users.update');
const canDelete = usePermission()('users.delete');

const handleDeleteUser = async (user: User) => {
    const confirmed = await confirmDelete(
        'Excluir usuário?',
        `Tem certeza que deseja excluir o usuário "${user.name}"?`
    );
    if (confirmed) {
        router.delete(users.destroy(user.id).url, {
            onSuccess: () => showDeleteSuccess('Usuário excluído!', 'O usuário foi excluído com sucesso.')
        });
    }
};

const handleViewUserCourses = (user: User) => {
    router.get(users.courses.index(user.id).url);
};
</script>

<template>
    <div class="block md:hidden space-y-4">
        <Item v-for="user in users_list" :key="user.id" variant="outline">
            <ItemHeader class="border-b-2">
                <ItemTitle>{{ user.name }}</ItemTitle>
                <ItemActions>
                    <Button variant="ghost" size="sm" class="cursor-pointer" @click="() => handleViewUserCourses(user)">
                        <Eye class="h-4 w-4 text-zinc-500 dark:text-zinc-700" />
                    </Button>
                    <UpdateUserDialog :user="user" :roles_list="roles_list"/>

                    <Button v-if="canDelete" variant="ghost" size="sm" class="cursor-pointer" @click="() => handleDeleteUser(user)">
                        <Trash class="h-4 w-4 text-red-500 dark:text-red-700" />
                    </Button>
                </ItemActions>
            </ItemHeader>
            <ItemContent>
                <div class="flex items-center gap-3">
                    <span class="font-medium text-foreground">Papéis</span>
                    <p class="text-xs font-semibold text-muted-foreground uppercase">
                        {{ user.roles.map(r => r.name).join(', ') }}
                    </p>
                </div>
            </ItemContent>
        </Item>
    </div>

    <div class="hidden md:block">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Nome</TableHead>
                    <TableHead>Papéis</TableHead>
                    <TableHead v-if="canEdit || canDelete">Ações</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="user in users_list" :key="user.id">
                    <TableCell>{{ user.id }}</TableCell>
                    <TableCell>{{ user.name }}</TableCell>
                    <TableCell>{{ user.roles.map(r => r.name).join(', ') }}</TableCell>
                    <TableCell v-if="canEdit || canDelete">
                        <Button variant="ghost" size="sm" class="cursor-pointer" @click="() => handleViewUserCourses(user)">
                            <Eye class="h-4 w-4 text-zinc-500 dark:text-zinc-700" />
                        </Button>
                        <UpdateUserDialog :user="user" :roles_list="roles_list"/>
                        <Button v-if="canDelete" variant="ghost" size="sm" class="cursor-pointer" @click="() => handleDeleteUser(user)">
                            <Trash class="h-4 w-4 text-red-500 dark:text-red-700" />
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
