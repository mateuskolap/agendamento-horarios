<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import users from '@/routes/users';
import { BreadcrumbItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
    {
        title: 'Informações',
        href: users.show(props.user.id).url
    }
];
</script>

<template>
    <Head title="Visualização Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout :title="user.name" description="Gerenciar informações do usuário">
            <template></template>
        </IndexLayout>
    </AppLayout>
</template>