<script lang="ts" setup>
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import courses from '@/routes/courses';
import users from '@/routes/users';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { GraduationCap, LayoutGrid, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePermission } from '@/composables/usePermissions';

const can = usePermission();

const mainNavItems = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    can('courses.index') && {
        title: 'Cursos',
        href: courses.index(),
        icon: GraduationCap,
    },
    can('users.index') && {
        title: 'Usuários',
        href: users.index(),
        icon: Users,
    },
].filter(Boolean) as NavItem[];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child class="justify-center" size="lg">
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
