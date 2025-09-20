<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { useNaiveTheme } from '@/composables/useNaiveTheme';
import ptBR from 'naive-ui/es/locales/common/ptBR';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { naiveTheme, themeOverrides } = useNaiveTheme();
</script>

<template>
    <NConfigProvider :theme="naiveTheme" :theme-overrides="themeOverrides" :locale="ptBR">
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="px-3 py-3">
                <slot />
            </div>
        </AppLayout>
    </NConfigProvider>
</template>
