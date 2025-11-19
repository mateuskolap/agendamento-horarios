<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { CalendarPlus } from 'lucide-vue-next';
import schedulings from '@/routes/schedulings';
import { router } from '@inertiajs/vue3';

function goToCreatePage() {
    router.get(schedulings.create().url);
}
</script>

<template>
    <div class="grid grid-cols-2 rounded-xl p-3 bg-zinc-200 dark:bg-zinc-900">
        <Heading title="Agendamentos" class="!mb-0" />
        <div class="flex justify-end">
            <Button variant="outline" @click="goToCreatePage">
                <CalendarPlus class="w-4 h-4 mr-2" />
                Nova Solicitação
            </Button>
        </div>
    </div>
</template>
