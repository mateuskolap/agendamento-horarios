<script setup lang="ts">
import UserCourseSyncDialog from '@/components/app/users/UserCourseSyncDialog.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import UserCourseLayout from '@/layouts/users/courses/Layout.vue';
import { translateArray } from '@/plugins/translate';
import users from '@/routes/users';
import { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import Role from '@/types/Role';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Label } from '@/components/ui/label';
import UserCourseList from '@/components/app/users/UserCourseList.vue';

const props = defineProps<{
    user: User;
    roles_list: Role[];
    user_courses: Course[];
    organizations_list: Organization[];
}>();

const roleOptions = computed(() =>
    translateArray(props.roles_list, ['name']).map((role) => ({
        label: role.name,
        value: role.id,
    })),
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
    {
        title: 'Informações',
        href: users.courses.index(props.user.id).url,
    },
];

const selectedRoleId = ref<number | null>(null);

const selectedRole = computed(() => {
    return props.roles_list.find((role) => role.id === selectedRoleId.value) ?? null;
});

// Faz a requisição sempre que o papel selecionado mudar
watch(selectedRoleId, (roleId) => {
    router.get(users.courses.index(props.user.id), {
        role_id: roleId ?? undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
});
</script>

<template>
    <Head title="Visualização Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <UserCourseLayout :user="user">
            <Label class="pb-2">Comece selecionando um papel para gerenciar/visualizar os cursos</Label>
            <div class="flex gap-2">
                <NSelect
                    v-model:value="selectedRoleId"
                    :options="roleOptions"
                    placeholder="Selecione um papel"
                    class="md:col-span-3"
                    clearable
                    filterable
                />
                <UserCourseSyncDialog
                    v-if="selectedRole && selectedRole.courses_editable"
                    :role="selectedRole"
                    :user="user"
                    :organizations_list="organizations_list"
                />
            </div>
            <div class="pt-6">
                <UserCourseList
                    v-if="selectedRole"
                    :user_courses="user_courses"
                    :selected_role="selectedRole"
                    :can_manage="selectedRole?.courses_editable ?? 0"
                />
            </div>
        </UserCourseLayout>
    </AppLayout>
</template>
