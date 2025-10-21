<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';

defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="rounded-t-xl text-center bg-zinc-200 dark:bg-zinc-900 p-2">
        <HeadingSmall :title="title" />
    </div>
</template>
