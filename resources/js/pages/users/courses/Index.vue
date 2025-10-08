<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import { useModal } from '@/composables/useModal';
import AppLayout from '@/layouts/AppLayout.vue';
import UserCourseLayout from '@/layouts/users/courses/Layout.vue';
import UserCourseList from '@/pages/users/components/UserCourseList.vue';
import CourseSyncModal from '@/pages/users/modals/CourseSyncModal.vue';
import { translateArray } from '@/plugins/translate';
import users from '@/routes/users';
import { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import Role from '@/types/Role';
import { Head, useForm } from '@inertiajs/vue3';
import { Link2 } from 'lucide-vue-next';
import { NSelect } from 'naive-ui';
import { computed, onMounted, ref } from 'vue';

const props = defineProps<{
    user: User;
    roles_list: Role[];
    user_courses: Course[];
    organizations_list: Organization[];
}>();

const rolesListPtBr = computed(() => translateArray(props.roles_list, ['name']) as any);

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

const form = useForm({
    role_id: null as number | null,
});

const selectedRole = ref<Role | null>(null);

function handleRoleChange(roleId?: number) {
    if (roleId) {
        selectedRole.value = props.roles_list.find((role) => role.id === roleId) || null;
        form.role_id = roleId;
    } else {
        selectedRole.value = null;
        form.role_id = null;
    }

    form.get(users.courses.index(props.user.id).url, {
        preserveState: true,
        preserveScroll: true,
    });
}

onMounted(() => {
    handleRoleChange();
})

const modal = useModal();

function openModal() {
    modal.open();
}

function closeModal() {
    modal.close();
}
</script>

<template>
    <Head title="Visualização Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        {{ selectedRole?.name }}
        <UserCourseLayout :user="user">
            <Label class="pb-2">Comece selecionando um papel para gerenciar/visualizar os cursos</Label>
            <div class="flex gap-2">
                <NSelect
                    :value="form.role_id"
                    @update:value="handleRoleChange"
                    class="md:col-span-3"
                    placeholder="Selecione um papel"
                    :options="rolesListPtBr"
                    label-field="name"
                    value-field="id"
                    clearable
                    filterable
                />
                <Button v-if="form.role_id && selectedRole?.can_manage_courses" variant="outline" class="cursor-pointer" @click="openModal()">
                    <Link2 />
                    Vincular Cursos
                </Button>
            </div>

            <div class="pt-6">
                <UserCourseList
                    :user_courses="user_courses"
                    :can_manage="selectedRole?.can_manage_courses ?? 0"
                    :selected_role="selectedRole || undefined"
                />
            </div>

            <CourseSyncModal
                v-if="selectedRole"
                v-bind="modal.modalBindInfo()"
                :user="user"
                :role="selectedRole"
                :organizations_list="organizations_list"
                @update:is-open="closeModal"
            />
        </UserCourseLayout>
    </AppLayout>
</template>
