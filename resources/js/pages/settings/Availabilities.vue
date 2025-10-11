<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import AvailabilitiesTabs from '@/pages/settings/components/AvailabilitiesTabs.vue';
import availabilities from '@/routes/availabilities';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

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
                    <AvailabilitiesTabs />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
