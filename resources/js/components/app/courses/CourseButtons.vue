<script setup lang="ts">
import CreateCourseDialog from '@/components/app/courses/CreateCourseDialog.vue';
import type { User } from '@/types';
import Organization from '@/types/Organization';

defineProps<{
    coordinators_list: User[];
    organizations_list: Organization[];
}>();
</script>

<template>
    <CreateCourseDialog
        :coordinators_list="coordinators_list"
        :organizations_list="organizations_list"
    />
</template>

<style scoped></style>
