<script setup lang="ts">
import { useFlash } from '@/composables/useFlash';
import { useNaiveTheme } from '@/composables/useNaiveTheme';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import ptBR from 'naive-ui/es/locales/common/ptBR';
import { watch } from 'vue';
import { Toaster, toast } from 'vue-sonner';
import 'vue-sonner/style.css';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const flash = useFlash();

watch(() => flash.value, (msg) => {
    if (!msg) return;

    if (msg.success) {
        toast.success('Sucesso', {
            description: msg.success
        });
    }
    if (msg.error) {
        toast.error('Erro', {
            description: msg.error
        });
    }
    if (msg.warning) {
        toast.warning('Atenção', {
            description: msg.warning
        });
    }
    if (msg.info) {
        toast.info('Informação', {
            description: msg.info
        });
    }
}, { immediate: true });

const { naiveTheme, themeOverrides } = useNaiveTheme();
</script>

<template>
    <NConfigProvider :theme="naiveTheme" :theme-overrides="themeOverrides" :locale="ptBR">
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="px-3 py-3 h-full">
                <slot />
            </div>
        </AppLayout>
        <Toaster
            position="top-right"
            theme="dark"
            expand
            richColors
            :duration="5000"
            closeButton
        />
    </NConfigProvider>
</template>
