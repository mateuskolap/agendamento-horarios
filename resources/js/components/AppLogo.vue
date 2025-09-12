<script setup lang="ts">
import { useSidebar } from '@/components/ui/sidebar/utils';
import { computed } from 'vue';

const { state } = useSidebar();

const logoSrc = computed(() => {
    return state.value === 'collapsed' ? '/unicv-folha.png' : '/unicv-logo-site.png';
});

const logoClasses = computed(() => {
    return state.value === 'collapsed' ? 'h-8' : 'h-12';
});
</script>

<template>
    <div class="flex items-center justify-center">
        <img
            :src="logoSrc"
            :class="logoClasses"
            alt="Logo UNICV"
            class="transition-all duration-200"
        />
    </div>
</template>
