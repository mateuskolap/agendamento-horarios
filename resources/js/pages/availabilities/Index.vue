<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { type BreadcrumbItem, User } from '@/types';
import Availability from '@/types/Availability';
import Role from '@/types/Role';
import { Head } from '@inertiajs/vue3';
import availabilities from '@/routes/availabilities';
import Buttons from '@/pages/availabilities/components/Buttons.vue';
import PaginatedData from '@/types/PaginatedData';
import Filters from '@/pages/availabilities/components/Filters.vue';

defineProps<{
    availabilities_list: PaginatedData<Availability>;
    users_list: User[];
    roles_list: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Disponibilidade',
        href: availabilities.index().url,
    },
];
</script>

<template>
    <Head title="Disponibilidade" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout title="Disponibilidades" description="Gerencie as disponibilidades">

            <template #buttons>
                <Buttons/>
            </template>

            <template #filters>
                <Filters :users_list="users_list" :roles_list="roles_list"/>
            </template>
        </IndexLayout>
    </AppLayout>
</template>
