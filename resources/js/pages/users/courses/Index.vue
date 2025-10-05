<script setup lang="ts">
import { BreadcrumbItem, User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import UserCourseLayout from "@/layouts/users/courses/Layout.vue"
import users from '@/routes/users';
import AppLayout from '@/layouts/AppLayout.vue';
import { NSelect } from 'naive-ui';
import Role from '@/types/Role';
import Course from '@/types/Course';
import Label from '@/components/ui/label/Label.vue';
import { computed, watch } from 'vue';
import { translateArray } from '@/plugins/translate';
import UserCourseList from '@/pages/users/components/UserCourseList.vue';

const props = defineProps<{
    user: User;
    roles_list: Role[];
    courses_list: Course[];
    selected_role?: Role;
}>();

const rolesListPtBr = computed(() => translateArray(props.roles_list, ['name']));
const rolesOptions = computed(() => (rolesListPtBr.value || []).map((r) => ({ label: r.name, value: r.id })));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
    {
        title: 'Informações',
        href: users.courses.index(props.user.id).url
    }
];

const form = useForm({
    role_id: null as number | null,
});

watch(() => form.role_id, () => {
    form.get(users.courses.index(props.user.id).url, { preserveState: true, preserveScroll: true })
})
</script>

<template>

    <Head title="Visualização Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <UserCourseLayout :user="user">
            <Label class="pb-2">Comece selecionando um papel para gerenciar/visualizar os cursos</Label>
            <NSelect v-model:value="form.role_id" class="md:col-span-3" placeholder="Selecione um papel"
                :options="rolesOptions" clearable filterable />

            <div class="pt-6">
                <UserCourseList :courses_list="courses_list" :can_manage="selected_role?.can_manage_courses ?? 0" />
            </div>

        </UserCourseLayout>
    </AppLayout>
</template>