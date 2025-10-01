<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import PageLayout from '@/layouts/PageLayout.vue';
import users from '@/routes/users';
import { BreadcrumbItem, User } from '@/types';

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
    {
        title: 'Visualização',
        href: users.show(props.user.id).url
    }
];
</script>

<template>
    <Head title="Visualização Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout :title="user.name">

        </PageLayout>
    </AppLayout>
</template>