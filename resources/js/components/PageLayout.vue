<script setup lang="ts">
import Heading from '@/components/Heading.vue';

defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="grid grid-cols-2">
        <Heading :title="title" :description="description" />
        <div v-if="$slots.buttons && $slots.buttons().length" class="flex justify-end gap-3 py-2">
            <slot name="buttons"></slot>
        </div>
    </div>
    <div v-if="$slots.filters && $slots.filters().length" class="gap-3 pb-5">
        <slot name="filters"></slot>
    </div>
    <div v-if="$slots.table && $slots.table().length" class="space-y-4">
        <slot name="table"></slot>
    </div>
</template>

<style scoped></style>
