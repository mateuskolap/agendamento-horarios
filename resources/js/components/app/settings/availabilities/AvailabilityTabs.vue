<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { Item, ItemActions, ItemContent, ItemDescription } from '@/components/ui/item';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AvailabilityHoursDialog from '@/components/app/settings/availabilities/AvailabilityHoursDialog.vue';
import Availability from '@/types/Availability';
import Weekday from '@/types/Weekday';
import { Trash } from '@vicons/ionicons5';

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
</script>

<template>
    <Tabs :default-value="1" class="w-full max-w-xl">
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
                <CardContent class="gap-3">
                    <Item variant="outline" class="p-2">
                        <ItemContent>
                            <ItemDescription> Apenas um simples teste </ItemDescription>
                        </ItemContent>
                        <ItemActions>
                            <Button variant="outline" size="sm" class="cursor-pointer">
                                <Trash />
                            </Button>
                        </ItemActions>
                    </Item>
                </CardContent>
            </Card>
        </TabsContent>
    </Tabs>
</template>
