<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { usePermission } from '@/composables/usePermissions';
import { Add } from '@vicons/ionicons5';

const emit = defineEmits<{
    (e: 'open-modal', availability: null): void;
}>();

const can = usePermission();
</script>

<template>
    <Button
        v-if="can('availabilities.create')"
        class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700"
        variant="outline"
        @click="emit('open-modal', null)"
    >
        <Add />
        Adicionar
    </Button>
</template>
