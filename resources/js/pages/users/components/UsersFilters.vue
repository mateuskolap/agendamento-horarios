<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import users from '@/routes/users';
import Role from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { Search } from '@vicons/ionicons5';
import { computed } from 'vue';

const props = defineProps<{
    roles_list: Role[];
}>();

const rolesOptions = computed(() => (props.roles_list || []).map((r) => ({ label: r.name, value: r.id })));

const form = useForm<{
    name: string;
    role_id: number | null;
}>({
    name: '',
    role_id: null,
});

const handleSubmit = () => {
    form.get(users.index().url, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-12 gap-3">
            <Input v-model="form.name" class="col-span-5" placeholder="Digite um nome" />
            <NSelect v-model:value="form.role_id" class="col-span-5" placeholder="Selecione um papel" :options="rolesOptions" clearable filterable />
            <Button class="col-span-2 cursor-pointer" variant="outline">
                <Search />
                Pesquisar
            </Button>
        </div>
    </form>
</template>

<style scoped></style>
