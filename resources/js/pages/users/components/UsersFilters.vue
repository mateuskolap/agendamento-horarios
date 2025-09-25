<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import users from '@/routes/users';
import Role from '@/types/Role';
import { router, useForm } from '@inertiajs/vue3';
import { Search, RefreshOutline } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
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
        <!-- Layout para telas grandes -->
        <div class="hidden md:grid md:grid-cols-12 gap-3">
            <Input v-model="form.name" class="col-span-5" placeholder="Digite um nome" />
            <NSelect v-model:value="form.role_id" class="col-span-5" placeholder="Selecione um papel" :options="rolesOptions" clearable filterable />
            <Button
                class="col-span-1 cursor-pointer"
                variant="outline"
                type="button"
                @click="router.get(users.index().url, {}, { preserveScroll: true })"
            >
                <RefreshOutline />
            </Button>
            <Button class="col-span-1 cursor-pointer" variant="outline">
                <Search />
            </Button>
        </div>

        <!-- Layout para telas pequenas -->
        <div class="md:hidden space-y-3">
            <Input v-model="form.name" placeholder="Digite um nome" />
            <NSelect v-model:value="form.role_id" placeholder="Selecione um papel" :options="rolesOptions" clearable filterable />
            <div class="flex gap-2">
                <Button
                    class="flex-1 cursor-pointer"
                    variant="outline"
                    type="button"
                    @click="router.get(users.index().url, {}, { preserveState: true, preserveScroll: true })"
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
