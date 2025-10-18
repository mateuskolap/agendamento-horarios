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
    <Label>
        {{ label }}
        <span v-if="required" class="text-red-500">*</span>
    </Label>
    <slot></slot>
    <InputError :message="inputError"/>
</template>
