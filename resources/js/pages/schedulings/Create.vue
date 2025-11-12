<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings as schedulingsList } from '@/routes';
import schedulings from '@/routes/schedulings';
import type { BreadcrumbItem, User } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Course from '@/types/Course';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: schedulingsList().url,
    },
    {
        title: 'Solicitação',
        href: schedulings.create().url,
    },
];

defineProps<{
    courses: Course[];
    educators: User[];
}>();

const form = useForm({
    requested_id: null as number | null,
    course_id: null as number | null,
    date: '',
    time: '',
    subject: '',
});

function submit() {
    form.post(schedulings.store().url);
}
</script>

<template>
    <Head title="Solicitar Agendamento" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout title="Solicitar Agendamento">
            <section class="max-w-2xl">
                <form @submit.prevent="submit">
                </form>
            </section>
        </IndexLayout>
    </AppLayout>
</template>
