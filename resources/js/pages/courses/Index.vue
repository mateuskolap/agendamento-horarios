<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Course from '@/types/Course';

defineProps<{
    courses: Course[],
}>();
</script>

<template>
    <Head title="Index" />
</template>
