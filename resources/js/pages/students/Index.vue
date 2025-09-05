<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import Student from '@/types/Student';

defineProps<{
    students: Student[];
}>();
</script>

<template>
    <Head title="Index" />
</template>
