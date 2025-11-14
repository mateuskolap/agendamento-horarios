<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings as schedulingsList } from '@/routes';
import courses from '@/routes/courses';
import schedulings from '@/routes/schedulings';
import type { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';

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
    educators: User[];
}>();

const form = useForm({
    requested_id: null as number | null,
    course_id: null as number | null,
    date: '',
    time: '',
    subject: '',
});

const availableCourses = ref<Course[]>([]);

watch(
    () => form.requested_id,
    async (requested_id) => {
        if (requested_id) {
            axios.get(courses.availableByRequested().url, {
                params: {
                    requested_id: requested_id,
                },
            }).then((response) => {
                availableCourses.value = response.data.data;
            });
        }
    },
);

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
                    <NSelect
                        v-model:value="form.requested_id"
                        :options="educators as any"
                        value-field="id"
                        label-field="name"
                        filterable
                        clearable
                        :to="false"
                    />
                    <pre>{{ courses }}</pre>
                    <pre>{{ educators }}</pre>
                </form>
            </section>
        </IndexLayout>
    </AppLayout>
</template>
