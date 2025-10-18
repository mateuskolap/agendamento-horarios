<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { schedulings } from '@/routes';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: schedulings().url,
    },
];
</script>

<template>
    <Head title="Agendamentos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout title="Agendamentos"> </IndexLayout>
    </AppLayout>
</template>
