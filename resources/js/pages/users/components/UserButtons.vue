<script setup lang="ts">
import { Add } from '@vicons/ionicons5';
import { Button } from '@/components/ui/button';

const emit = defineEmits<{
    (e: 'open-modal', user: null): void;
}>();
</script>

<template>
    <Button class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" variant="outline" @click="emit('open-modal', null)">
        <Add />
        Adicionar
    </Button>
</template>

<style scoped>

</style>
