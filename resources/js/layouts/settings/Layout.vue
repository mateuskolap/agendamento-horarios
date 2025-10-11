<script setup lang="ts">
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import SidebarLayout from '@/layouts/SidebarLayout.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const mustChangePassword = page.props.auth.user.must_change_password;

const sidebarNavItems: NavItem[] = mustChangePassword
    ? [
        {
            title: 'Senha',
            href: editPassword(),
        },
    ]
    : [
        {
            title: 'Perfil',
            href: edit(),
        },
        {
            title: 'Senha',
            href: editPassword(),
        },
    ];
</script>

<template>
    <SidebarLayout :sidebar-nav-items="sidebarNavItems" nav-title="Configurações"
        nav-description="Gerencie as suas configurações">
        <section class="max-w-xl space-y-12">
            <slot></slot>
        </section>
    </SidebarLayout>
</template>
