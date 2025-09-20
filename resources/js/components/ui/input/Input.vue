<script lang="ts" setup>
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <input
    v-model="modelValue"
    :class="cn(
      'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input flex h-9 w-full min-w-0',
      'rounded-md border border-black dark:border-[var(--color-ring)] p-3',
      'focus:outline-none focus:border-transparent focus:ring-inset focus:ring-[2px] focus:ring-[var(--color-ring)] focus:ring-offset-0',
      props.class,
    )"
    data-slot="input"
  >
</template>
