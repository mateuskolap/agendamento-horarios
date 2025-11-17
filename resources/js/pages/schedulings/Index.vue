<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import type Scheduling from '@/types/Scheduling';
import { Head, usePage } from '@inertiajs/vue3';
import SchedulingTitle from '@/components/app/schedulings/SchedulingTitle.vue';
import UpcomingSchedulings from '@/components/app/schedulings/UpcomingSchedulings.vue';
import SchedulingCalendar from '@/components/app/schedulings/SchedulingCalendar.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: schedulings().url,
    },
];

const props = defineProps<{
    requested_schedulings: Scheduling[];
    received_schedulings: Scheduling[];
}>();
</script>

<template>
    <Head title="Agendamentos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout class="h-full">
            <div class="flex flex-col h-full gap-5">
                <SchedulingTitle/>
                <div class="grid flex-1 min-h-0">
                    <UpcomingSchedulings
                        :requested-schedulings="props.requested_schedulings"
                        :received-schedulings="props.received_schedulings"
                    />
<!--                    <SchedulingCalendar-->
<!--                        class="md:col-span-2"-->
<!--                        :requested-schedulings="props.requested_schedulings"-->
<!--                        :received-schedulings="props.received_schedulings"-->
<!--                    />-->
                </div>
            </div>
        </IndexLayout>
    </AppLayout>
</template>

