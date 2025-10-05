<script setup lang="ts">
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import Course from '@/types/Course';
import { Link2Off } from 'lucide-vue-next';
import { NButton, NDataTable, NIcon, type DataTableColumns } from 'naive-ui';
import { computed, h } from 'vue';

const props = defineProps<{
    courses_list: Course[];
    can_manage: number;
}>();

const { confirmDelete } = useConfirmDelete();

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
                            onClick: () => confirmDelete(''),
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
        <NDataTable :columns="columns" :data="courses_list"/>
    </div>
</template>