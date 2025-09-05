<script lang="ts" setup>
import Course from '@/types/Course';
import { Head } from '@inertiajs/vue3';

defineProps<{
    courses: Course[];
}>();
</script>

<template>
    <Head title="Cursos - Agendamento de Horários" />
</template>

<style scoped></style>
