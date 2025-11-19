<script lang="ts" setup>
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';

const props = defineProps<{
  defaultValue?: string
  modelValue?: string
  class?: HTMLAttributes['class']
  disabled?: boolean
  placeholder?: string
  rows?: number
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <textarea
    v-model="modelValue"
    :disabled="disabled"
    :placeholder="placeholder"
    :rows="rows || 3"
    :class="cn(
      'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input flex w-full min-w-0',
      'rounded-md border border-black dark:border-[var(--color-ring)] p-3',
      'focus:outline-none focus:border-transparent focus:ring-inset focus:ring-[2px] focus:ring-[var(--color-ring)] focus:ring-offset-0',
      'disabled:cursor-not-allowed disabled:opacity-50',
      'resize-none',
      props.class,
    )"
    data-slot="textarea"
  />
</template>
