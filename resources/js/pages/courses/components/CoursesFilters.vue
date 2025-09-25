<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import courses from '@/routes/courses';
import Coordinator from '@/types/Coordinator';
import Organization from '@/types/Organization';
import { router, useForm } from '@inertiajs/vue3';
import { Search, RefreshOutline } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import { computed } from 'vue';

const props = defineProps<{
    coordinators_list: Coordinator[];
    organizations_list: Organization[];
}>();

const coordinatorsOptions = computed(() => (props.coordinators_list || []).map((c) => ({ label: c.user.name, value: c.id })));
const organizationsOptions = computed(() => (props.organizations_list || []).map((o) => ({ label: o.name, value: o.id })));

const form = useForm<{
    name: string;
    coordinator_id: number | null;
    organization_id: number | null;
}>({
    name: '',
    coordinator_id: null,
    organization_id: null,
});

const handleSubmit = () => {
    form.get(courses.index().url, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <!-- Layout para telas grandes -->
        <div class="hidden md:grid md:grid-cols-12 gap-3">
            <Input v-model="form.name" class="col-span-4" placeholder="Nome do curso" />
            <NSelect v-model:value="form.coordinator_id" class="col-span-3" placeholder="Coordenador" :options="coordinatorsOptions" clearable filterable />
            <NSelect v-model:value="form.organization_id" class="col-span-3" placeholder="Organização" :options="organizationsOptions" clearable filterable />
            <Button
                class="col-span-1 cursor-pointer"
                variant="outline"
                type="button"
                @click="router.get(courses.index().url, {}, { preserveState: true, preserveScroll: true })"
            >
                <RefreshOutline />
            </Button>
            <Button class="col-span-1 cursor-pointer" variant="outline">
                <Search />
            </Button>
        </div>

        <!-- Layout para telas pequenas -->
        <div class="md:hidden space-y-3">
            <Input v-model="form.name" placeholder="Nome do curso" />
            <NSelect v-model:value="form.coordinator_id" placeholder="Coordenador" :options="coordinatorsOptions" clearable filterable />
            <NSelect v-model:value="form.organization_id" placeholder="Organização" :options="organizationsOptions" clearable filterable />
            <div class="flex gap-2">
                <Button
                    class="flex-1 cursor-pointer"
                    variant="outline"
                    type="button"
                    @click="router.get(courses.index().url, {}, { preserveState: true, preserveScroll: true })"
                >
                    <RefreshOutline />
                    Limpar
                </Button>
                <Button class="flex-1 cursor-pointer" variant="outline">
                    <Search />
                    Pesquisar
                </Button>
            </div>
        </div>
    </form>
</template>

<style scoped></style>
