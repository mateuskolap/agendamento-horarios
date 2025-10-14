<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { useNaiveTheme } from '@/composables/useNaiveTheme';
import ptBR from 'naive-ui/es/locales/common/ptBR';
import { useFlash } from '@/composables/useFlash';
import { watch } from 'vue';
import Swal from 'sweetalert2';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const flash = useFlash();

watch(flash, (msg) => {
    if (!msg) return;

    if (msg.success) {
        Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: msg.success,
            timer: 1500,
            showConfirmButton: false,
            position: "top-end",
            width: 400,
            padding: '1em',
        });
    }

    if (msg.info) {
        Swal.fire({
            icon: "info",
            title: "Informação",
            text: msg.info,
            timer: 3000,
            showConfirmButton: false,
            position: "top-end",
            width: 400,
            padding: '1em',
        });
    }

    if (msg.warning) {
        Swal.fire({
            icon: "warning",
            title: "Atenção",
            text: msg.warning,
            timer: 3000,
            showConfirmButton: false,
            position: "top-end",
            width: 400,
            padding: '1em',
        });
    }

    if (msg.error) {
        Swal.fire({
            icon: "error",
            title: "Erro",
            text: msg.error,
            timer: 3000,
            showConfirmButton: false,
            position: "top-end",
            width: 400,
            padding: '1em',
        });
    }
})

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
