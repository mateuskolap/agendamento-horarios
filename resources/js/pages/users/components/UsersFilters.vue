<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';
import Role from '@/types/Role';
import { Button } from '@/components/ui/button';
import { Search } from '@vicons/ionicons5';
import { computed } from 'vue';

const props = defineProps<{
    roles_list: Role[];
}>();

const rolesOptions = computed(() => (props.roles_list || []).map((r) => ({ label: r.name, value: r.id })));

const form = useForm({
    name: '',
    role: '',
});
</script>

<template>
    <div class="grid grid-cols-12 gap-3">
        <Input v-model="form.name" class="col-span-5" placeholder="Digite um nome" />
        <NSelect v-model="form.role" class="col-span-5" placeholder="Selecione um papel" :options="rolesOptions" />
        <Button class="cursor-pointer col-span-2" variant="outline">
            <Search/>
            Pesquisar
        </Button>
    </div>
</template>

<style scoped></style>
