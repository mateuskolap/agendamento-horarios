<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Course from '@/types/Course';
import PaginatedData from '@/types/PaginatedData';

defineProps<{
    courses: PaginatedData<Course>,
}>();
</script>

<template>
    <Head title="Index" />
</template>
