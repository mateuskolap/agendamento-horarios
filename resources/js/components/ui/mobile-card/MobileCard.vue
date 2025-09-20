<script lang="ts" setup>
</script>

<template>
    <div class="rounded-md border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-neutral-900">
        <div class="flex items-start justify-between">
            <div class="min-w-0">
                <slot name="header-left" />
            </div>
            <div class="flex shrink-0 gap-1">
                <slot name="header-right" />
            </div>
        </div>

        <div class="mt-1 grid grid-cols-1 gap-1 text-sm">
            <slot />
        </div>
    </div>
</template>
