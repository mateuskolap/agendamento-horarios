<script setup lang="ts">
import { Label } from '@/components/ui/label';
import InputError from './InputError.vue';

defineProps<{
    label?: string;
    inputError?: string;
    required?: boolean;
}>();
</script>

<template>
    <div class="space-y-2">
        <Label>
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </Label>
        <slot />
    </div>
    <InputError :message="inputError" />
</template>
