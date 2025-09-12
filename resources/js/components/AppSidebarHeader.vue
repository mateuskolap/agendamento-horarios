<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const { appearance, updateAppearance } = useAppearance();

const isDarkMode = computed(() => {
    if (appearance.value === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    return appearance.value === 'dark';
});

const toggleTheme = () => {
    const newTheme = isDarkMode.value ? 'light' : 'dark';
    updateAppearance(newTheme);
};

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2 flex-1">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <!-- Botão de troca de tema -->
        <button
            @click="toggleTheme"
            class="inline-flex cursor-pointer items-center justify-center rounded-md p-2 text-sm transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            :title="isDarkMode ? 'Alterar para tema claro' : 'Alterar para tema escuro'"
        >
            <Sun v-if="isDarkMode" class="h-4 w-4" />
            <Moon v-else class="h-4 w-4" />
        </button>
    </header>
</template>
