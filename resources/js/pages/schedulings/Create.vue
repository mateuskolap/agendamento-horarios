<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings as schedulingsList } from '@/routes';
import schedulings from '@/routes/schedulings';
import type { BreadcrumbItem } from '@/types';
import Role from '@/types/Role';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import EducatorSelection from '@/components/app/schedulings/creation_steps/EducatorSelection.vue';

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
    roles: Role[];
}>();

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    student_id: user.id,
    educator_id: null as number | null,
    educator_role_id: null as number | null,
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
                    <EducatorSelection :form="form" :roles="roles"/>
                </form>
            </section>
        </IndexLayout>
    </AppLayout>
</template>
