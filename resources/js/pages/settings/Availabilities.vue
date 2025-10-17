<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import AvailabilityTabs from '@/components/app/settings/availabilities/AvailabilityTabs.vue';
import availabilities from '@/routes/availabilities';
import type { BreadcrumbItem } from '@/types';
import Availability from '@/types/Availability';
import { Head } from '@inertiajs/vue3';

defineProps<{
    availabilities_list: Availability[];
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Ajuste de Disponibilidade',
        href: availabilities.index().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Ajustes de Disponibilidade" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    description="Defina os horários que você está disponível para compromissos"
                    title="Gerenciar Disponibilidades de Horário"
                />

                <div class="space-y-6">
                    <AvailabilityTabs :availabilities_list="availabilities_list" />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
