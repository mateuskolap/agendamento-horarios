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

const refresh = () => router.get(users.index().url, {}, { preserveScroll: true });
const submit = () => form.get(users.index().url, { preserveState: true, preserveScroll: true });
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <Input v-model="form.name" class="md:col-span-5" placeholder="Nome do usuário" />
            <NSelect
                v-model:value="form.role_id"
                class="md:col-span-5"
                placeholder="Papel"
                :options="rolesOptions"
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

<style scoped></style>
