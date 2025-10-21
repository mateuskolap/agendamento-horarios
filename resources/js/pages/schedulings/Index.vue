<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import SchedulingTitle from '@/components/app/schedulings/SchedulingTitle.vue';
import UpcomingSchedulings from '@/components/app/schedulings/UpcomingSchedulings.vue';
import SchedulingCalendar from '@/components/app/schedulings/SchedulingCalendar.vue';

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
        <IndexLayout class="h-full">
            <div class="flex flex-col h-full gap-5">
                <SchedulingTitle/>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 flex-1 min-h-0">
                    <UpcomingSchedulings/>
                    <SchedulingCalendar class="md:col-span-2"/>
                </div>
            </div>
        </IndexLayout>
    </AppLayout>
</template>
