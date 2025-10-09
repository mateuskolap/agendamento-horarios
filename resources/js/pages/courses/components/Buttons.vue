<script setup lang="ts">
import { Add } from '@vicons/ionicons5';
import { Button } from '@/components/ui/button';
import { usePermission } from '@/composables/usePermissions';

const emit = defineEmits<{
    (e: 'open-modal', course: null): void;
}>();

const can = usePermission();
</script>

<template>
    <Button v-if="can('courses.create')" class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700"
        variant="outline" @click="emit('open-modal', null)">
        <Add />
        Adicionar
    </Button>
</template>

<style scoped></style>
