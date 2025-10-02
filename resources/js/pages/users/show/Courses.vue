<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import users from '@/routes/users';
import { BreadcrumbItem, NavItem, User } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    user: User;
}>();

const userCourses = props.user.courses

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: users.index().url,
    },
    {
        title: 'Informações',
        href: users.courses.index(props.user.id).url
    }
];

const sidebarNavItems: NavItem[] = [
    {
        title: 'Cursos',
        href: users.courses.index(props.user.id),
    }
];
</script>

<template>

    <Head title="Visualização Usuário" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <ShowLayout :title="user.name" description="Gerencie as informações do usuário"
            :sidebar-nav-items="sidebarNavItems">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Cursos do Usuário" description="Visualize, adicione e remova cursos" />

                <div class="space-y-6">
                    <pre>{{ userCourses }}</pre>
                </div>
            </div>
        </ShowLayout>
    </AppLayout>
</template>