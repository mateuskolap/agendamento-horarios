<script setup lang="ts">
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import { cn } from '@/lib/utils';

const props = defineProps<{
    modelValue?: Date | string;
    class?: string;
    minDate?: Date;
    disabled?: boolean;
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: Date): void;
}>();

const handleUpdate = (date: Date) => {
    emits('update:modelValue', date);
};
</script>

<template>
    <DatePicker
        :model-value="modelValue"
        @update:model-value="handleUpdate"
        :min-date="minDate"
        :disabled="disabled"
        :class="cn('w-full', props.class)"
        mode="date"
        :masks="{ input: 'DD/MM/YYYY' }"
    >
        <template #default="{ inputValue, inputEvents }">
            <input
                :value="inputValue"
                v-on="inputEvents"
                :disabled="disabled"
                :class="
                    cn(
                        'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input flex h-9 w-full min-w-0',
                        'rounded-md border border-black dark:border-[var(--color-ring)] p-3',
                        'focus:outline-none focus:border-transparent focus:ring-inset focus:ring-[2px] focus:ring-[var(--color-ring)] focus:ring-offset-0',
                        disabled && 'opacity-50 cursor-not-allowed',
                    )
                "
                placeholder="Selecione uma data"
            />
        </template>
    </DatePicker>
</template>
