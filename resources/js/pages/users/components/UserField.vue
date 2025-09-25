<script setup lang="ts">
defineProps<{
    label: string;
    value?: string | number | null;
}>();
</script>

<template>
    <div class="space-y-1">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</p>
        <p class="text-sm text-gray-900 dark:text-gray-100">{{ value || '-' }}</p>
    </div>
</template>
