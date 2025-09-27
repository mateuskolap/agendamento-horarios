<script lang="ts" setup>
defineProps<{
    label: string;
    value?: string | number;
}>();
</script>

<template>
    <div class="[&_span]:text-gray-600 [&_span]:dark:text-gray-300 [&_span.font-label]:font-medium [&_span.value]:ml-1 [&_span.value]:truncate">
        <span class="font-label">{{ label }}:</span>
        <span class="value">{{ value ?? '-' }}</span>
    </div>
</template>
