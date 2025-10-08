<script setup lang="ts">
import MobileCardList from '@/components/ui/mobile-card/MobileCardList.vue';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { usePermission } from '@/composables/usePermissions';
import courses from '@/routes/courses';
import Course from '@/types/Course';
import { router } from '@inertiajs/vue3';
import { Pencil, Trash } from '@vicons/ionicons5';
import { NButton, NDataTable, NIcon } from 'naive-ui';
import { h } from 'vue';
import CourseField from './CourseField.vue';

defineProps<{
    courses_list: Course[];
}>();

const { confirmDelete, showDeleteSuccess } = useConfirmDelete();
const emit = defineEmits<{
    (e: 'open-modal', course?: Course): void;
}>();

const canEdit = usePermission()('courses.update');
const canDelete = usePermission()('courses.delete');

const handleDeleteCourse = async (course: Course) => {
    const confirmed = await confirmDelete('Excluir curso?', `Tem certeza que deseja excluir o curso "${course.name}"?`);

    if (confirmed) {
        router.delete(courses.destroy(course.id).url, {
            onSuccess: () => showDeleteSuccess('Curso excluído!', 'O curso foi excluído com sucesso.')
        });
    }
};

const columns = [
    { title: 'ID', key: 'id' },
    { title: 'Organização', key: 'organization.name' },
    { title: 'Curso', key: 'name' },
    { title: 'Coordenador', key: 'coordinator.name' },
    {
        title: 'Ações',
        key: 'actions',
        render(row: Course) {
            if (!canEdit && !canDelete) return null;
            return h(
                'div',
                { class: 'flex gap-2' },
                [
                    canEdit &&
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
                    canDelete &&
                        h(
                            NButton,
                            {
                                tertiary: true,
                                size: 'small',
                                onClick: () => handleDeleteCourse(row),
                            },
                            {
                                icon: () => h(NIcon, { class: 'text-red-500 dark:text-red-700' }, { default: () => h(Trash) }),
                            },
                        ),
                ].filter(Boolean),
            );
        },
    },
];
</script>

<template>
    <div class="hidden md:block">
        <NDataTable :columns="columns" :data="courses_list" size="small" />
    </div>

    <div class="md:hidden">
        <MobileCardList :items="courses_list" class="space-y-3" item-key="id">
            <template #header-left="{ item: course }">
                <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ course.id }}. {{ course.name }}</p>
            </template>

            <template #header-right="{ item: course }">
                <NButton v-if="canEdit" size="small" tertiary @click="emit('open-modal', course)">
                    <template #icon>
                        <NIcon><Pencil /></NIcon>
                    </template>
                </NButton>
                <NButton v-if="canDelete" size="small" tertiary @click="handleDeleteCourse(course)">
                    <template #icon>
                        <NIcon class="text-red-500 dark:text-red-700"><Trash /></NIcon>
                    </template>
                </NButton>
            </template>

            <template #default="{ item: course }">
                <CourseField :value="course.organization?.name" label="Organização" />
                <CourseField :value="course.coordinator?.name" label="Coordenador" />
            </template>
        </MobileCardList>
    </div>
</template>

<style scoped></style>
