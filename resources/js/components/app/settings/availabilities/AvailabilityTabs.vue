<script setup lang="ts">
import AvailabilityHoursDialog from '@/components/app/settings/availabilities/AvailabilityHoursDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import Availability from '@/types/Availability';
import Weekday from '@/types/Weekday';
import { Trash } from '@vicons/ionicons5';
import { router } from '@inertiajs/vue3';
import availabilities from '@/routes/availabilities';
import { ref, watch } from 'vue';

defineProps<{
    availabilities_list: Availability[];
}>();

const weekdays: Weekday[] = [
    { value: 1, label: 'Seg', full: 'Segunda-Feira' },
    { value: 2, label: 'Ter', full: 'Terça-Feira' },
    { value: 3, label: 'Qua', full: 'Quarta-Feira' },
    { value: 4, label: 'Qui', full: 'Quinta-Feira' },
    { value: 5, label: 'Sex', full: 'Sexta-Feira' },
    { value: 6, label: 'Sáb', full: 'Sábado' },
];

function handleDelete(availabilityId: number) {
    router.delete(availabilities.destroy(availabilityId).url, {
        preserveScroll: true,
    });
}

const selectedWeekday = ref<number>(1);

watch(selectedWeekday, (weekday) => {
    router.get(availabilities.index(), { weekday: weekday}, {
        preserveState: true,
        preserveScroll: true,
    })
})
</script>

<template>
    <Tabs v-model="selectedWeekday" class="w-full max-w-xl">
        <TabsList class="grid w-full grid-cols-6">
            <TabsTrigger v-for="weekday in weekdays" :key="weekday.value" :value="weekday.value" class="cursor-pointer hover:bg-sidebar-accent">
                {{ weekday.label }}
            </TabsTrigger>
        </TabsList>

        <TabsContent v-for="weekday in weekdays" :key="`content-${weekday.value}`" :value="weekday.value">
            <Card>
                <div class="flex items-center justify-between px-6">
                    <CardTitle class="text-xl">{{ weekday.full }}</CardTitle>
                    <AvailabilityHoursDialog :weekday="weekday" />
                </div>
                <CardContent class="flex flex-wrap gap-3 pt-4">
                    <div
                        v-for="availability in availabilities_list"
                        :key="availability.id"
                        class="flex items-center justify-between gap-3 rounded-md bg-muted px-3 py-2 text-sm font-medium"
                    >
                        <span class="font-mono"> {{ availability.start_time }} - {{ availability.end_time }} </span>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="h-6 w-6 text-muted-foreground hover:text-red-500"
                            title="Remover horário"
                            @click="handleDelete(availability.id)"
                        >
                            <Trash class="h-4 w-4" />
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </TabsContent>
    </Tabs>
</template>
