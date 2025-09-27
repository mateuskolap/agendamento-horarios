<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import courses from '@/routes/courses';
import Coordinator from '@/types/Coordinator';
import Organization from '@/types/Organization';
import { router, useForm } from '@inertiajs/vue3';
import { RefreshOutline, Search } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import { computed } from 'vue';

const props = defineProps<{
    coordinators_list: Coordinator[];
    organizations_list: Organization[];
}>();

const coordinatorsOptions = computed(() => (props.coordinators_list || []).map((c) => ({ label: c.user.name, value: c.id })));
const organizationsOptions = computed(() => (props.organizations_list || []).map((o) => ({ label: o.name, value: o.id })));

const form = useForm({
    name: '',
    coordinator_id: null as number | null,
    organization_id: null as number | null,
});

const refresh = () => router.get(courses.index().url, {}, { preserveScroll: true });
const submit = () => form.get(courses.index().url, { preserveState: true, preserveScroll: true });
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <Input v-model="form.name" class="md:col-span-4" placeholder="Nome do curso" />
            <NSelect
                v-model:value="form.coordinator_id"
                class="md:col-span-3"
                placeholder="Coordenador"
                :options="coordinatorsOptions"
                clearable
                filterable
            />
            <NSelect
                v-model:value="form.organization_id"
                class="md:col-span-3"
                placeholder="Organização"
                :options="organizationsOptions"
                clearable
                filterable
            />
            <div class="grid grid-cols-2 md:contents gap-3">
                <Button
                    class="md:col-span-1 cursor-pointer"
                    variant="outline"
                    type="button"
                    @click="refresh"
                >
                    <RefreshOutline />
                </Button>
                <Button class="md:col-span-1 cursor-pointer" variant="outline">
                    <Search />
                </Button>
            </div>
        </div>
    </form>
</template>
