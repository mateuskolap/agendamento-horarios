<script setup lang="ts">
import { NButton, NDataTable, NIcon } from 'naive-ui';
import courses from '@/routes/courses';
import Course from '@/types/Course';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { h } from 'vue';
import { Pencil, Trash } from '@vicons/ionicons5';
import MobileCardList from '@/components/ui/mobile-card/MobileCardList.vue';
import CourseField from './CourseField.vue';

defineProps<{
    courses_list: Course[];
}>();

const { confirmDelete } = useConfirmDelete();

const emit = defineEmits<{
    (e: 'open-modal', course?: Course): void;
}>();

const columns = [
    { title: 'ID', key: 'id' },
    { title: 'Organização', key: 'organization.name' },
    { title: 'Nome', key: 'name' },
    { title: 'Coordenador', key: 'coordinator.user.name' },
    {
        title: 'Ações',
        key: 'actions',
        render(row: Course) {
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
                        onClick: () => confirmDelete(courses.destroy(row.id).url),
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
        <NDataTable :columns="columns" :data="courses_list" size="small" />
    </div>

    <div class="md:hidden">
        <MobileCardList :items="courses_list" class="space-y-3" item-key="id">
            <template #header-left="{ item: course }">
                <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ course.id }}. {{ course.name }}</p>
            </template>

            <template #header-right="{ item: course }">
                <NButton size="small" tertiary @click="emit('open-modal', course)">
                    <template #icon>
                        <NIcon><Pencil /></NIcon>
                    </template>
                </NButton>
                <NButton size="small" tertiary @click="confirmDelete(courses.destroy(course.id).url)">
                    <template #icon>
                        <NIcon class="text-red-500 dark:text-red-700"><Trash /></NIcon>
                    </template>
                </NButton>
            </template>

            <template #default="{ item: course }">
                <CourseField :value="course.organization?.name" label="Organização" />
                <CourseField :value="course.coordinator?.user?.name" label="Coordenador" />
            </template>
        </MobileCardList>
    </div>
</template>

<style scoped></style>
