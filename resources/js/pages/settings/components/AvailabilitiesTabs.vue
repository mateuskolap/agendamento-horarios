<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

// const form = useForm({
//     user_id: null as number | null,
//     weekday: '',
//     time: '',
// });

const tabs = [
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
            <TabsTrigger v-for="tab in tabs" :key="tab.value" :value="tab.value" class="cursor-pointer hover:bg-sidebar-accent dark:hover:bg-accent">
                {{ tab.label }}
            </TabsTrigger>
        </TabsList>

        <TabsContent v-for="tab in tabs" :key="`content-${tab.value}`" :value="tab.value">
            <Card>
                <CardHeader>
                    <CardTitle>{{ tab.full }}</CardTitle>
                </CardHeader>
                <CardContent class="grid grid-cols-3 gap-4">
                </CardContent>
            </Card>
        </TabsContent>
    </Tabs>
</template>
