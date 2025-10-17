<script setup lang="ts">
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import users from '@/routes/users';
import Course from '@/types/Course';
import { Link2Off } from 'lucide-vue-next';
import { NButton, NDataTable, NIcon, type DataTableColumns } from 'naive-ui';
import { computed, h } from 'vue';
import Role from '@/types/Role';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    user_courses: Course[];
    selected_role?: Role;
    can_manage: number;
}>();

const { confirmDelete, showDeleteSuccess } = useConfirmDelete();

const handleDetachCourse = async (course: Course) => {
    const confirmed = await confirmDelete(
        'Desvincular curso?',
        `Tem certeza que deseja desvincular o curso "${course.name}" deste usuário?`
    );

    if (confirmed) {
        const deleteData: any = {};
        if (props.selected_role?.id) {
            deleteData.role_id = props.selected_role.id;
        }

        router.delete(users.courses.destroy({ user: course.pivot.user_id, course: course.id }).url, {
            data: deleteData,
            onSuccess: () => showDeleteSuccess('Curso desvinculado!', 'O curso foi desvinculado do usuário com sucesso.')
        });
    }
};

const columns = computed(() => {
    const baseColumns: DataTableColumns<Course> = [
        {title: 'Organização', key: 'organization.name'},
        {title: 'Curso', key: 'name'},
    ];

    if (props.can_manage) {
        baseColumns.push({
            title: 'Ações',
            key: 'actions',
            render(row: Course) {
                return h('div', { class: 'flex gap-2' }, [
                    h(
                        NButton,
                        {
                            tertiary: true,
                            size: 'small',
                            onClick: () => handleDetachCourse(row),
                        },
                        {
                            default: () => h(NIcon, { class: 'text-red-500 dark:text-red-700' }, { default: () => h(Link2Off) }),
                        },
                    ),
                ]);
            },
        });
    }

    return baseColumns;
});
</script>

<template>
    <div>
        <NDataTable :columns="columns" :data="user_courses"/>
    </div>
</template>
