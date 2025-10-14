<script setup lang="ts">
import SidebarLayout from '@/layouts/SidebarLayout.vue';
import users from '@/routes/users';
import { User, type NavItem } from '@/types';

const props = defineProps<{
    user: User;
}>();

const sidebarNavItems: NavItem[] = [
    {
        title: 'Cursos',
        href: users.courses.index(props.user),
    },
];
</script>

<template>
    <SidebarLayout :sidebar-nav-items="sidebarNavItems" :nav-title="user.name"
        nav-description="Navegue pelas opções do usuário para gerenciar as informações">
        <section class="max-w-xl">
            <slot></slot>
        </section>
    </SidebarLayout>
</template>
