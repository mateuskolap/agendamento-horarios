<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import Course = App.Models.Course;

defineProps<{
    courses: Course[];
}>();
</script>

<template>
    <Head title="Cursos - Agendamento de Horários" />
    {{ courses }}
</template>

<style scoped></style>
