<script setup lang="ts">
import { User } from '@/types';
import Role from '@/types/Role';
import { router, useForm } from '@inertiajs/vue3';
import availabilities from '@/routes/availabilities';
import { NSelect } from 'naive-ui';
import { RefreshOutline, Search } from '@vicons/ionicons5';
import { Button } from '@/components/ui/button';

defineProps<{
    users_list: User[];
    roles_list: Role[];
}>();

const form = useForm({
    user_id: null as number | null,
    role_id: null as number | null,
});

const refresh = () => router.get(availabilities.index().url, {}, { preserveScroll: true });
const submit = () => form.get(availabilities.index().url, { preserveState: true, preserveScroll: true });
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
            <NSelect
                v-model:value="form.user_id"
                class="md:col-span-5"
                placeholder="Usuário"
                :options="users_list"
                label-field="name"
                value-field="id"
                clearable
                filterable
            />
            <NSelect
                v-model:value="form.role_id"
                class="md:col-span-5"
                placeholder="Papel"
                :options="roles_list"
                label-field="name"
                value-field="id"
                clearable
                filterable
            />
            <div class="grid grid-cols-2 gap-3 md:contents">
                <Button class="cursor-pointer md:col-span-1" variant="outline" type="button" @click="refresh">
                    <RefreshOutline />
                </Button>
                <Button class="cursor-pointer md:col-span-1" variant="outline">
                    <Search />
                </Button>
            </div>
        </div>
    </form>
</template>
