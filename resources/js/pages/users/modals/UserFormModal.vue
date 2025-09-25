<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Modal from '@/components/ui/modal/Modal.vue';
import users from '@/routes/users/';
import type { User } from '@/types';
import Role from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { NSelect } from 'naive-ui';
import { computed, watch } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    user: User | null;
    roles_list: Role[];
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const modalTitle = computed(() => {
    return props.mode === 'create' ? 'Adicionar Usuário' : 'Editar Usuário';
});

const rolesOptions = computed(() => (props.roles_list || []).map((r) => ({ label: r.name, value: r.id })));

const closeModal = () => emit('update:is-open', false);

const form = useForm<{
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    roles: number[];
}>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
});

watch(
    () => [props.user, props.mode, props.isOpen] as const,
    ([user, mode, isOpen]) => {
        if (!isOpen) return;
        if (mode === 'edit' && user) {
            form.name = user.name;
            form.email = user.email;
            form.password = '';
            form.password_confirmation = '';
            form.roles = user.roles.map((r) => r.id);
            return;
        }

        form.name = '';
        form.email = '';
        form.password = '';
        form.password_confirmation = '';
        form.roles = [];
    },
    { immediate: true },
);

function handleSubmit() {
    if (props.mode === 'create') {
        form.post(users.store().url, {
            onSuccess: () => closeModal(),
        });
        return;
    }

    if (props.user) {
        form.put(users.update(props.user.id).url, {
            onSuccess: () => closeModal(),
        });
    }
    return;
}
</script>

<template>
    <Modal :id="`${mode}UserModal`" :is-open="isOpen" :title="modalTitle" size="md" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-3">
                <Input v-model="form.name" placeholder="Digite o Nome do Usuário" />
                <Input v-model="form.email" type="email" placeholder="Digite o E-mail" />
                <Input v-model="form.password" type="password" :placeholder="mode === 'edit' ? 'Digite a Nova Senha (deixe vazio para manter)' : 'Digite a Senha'" />
                <Input v-if="mode === 'create' || form.password" v-model="form.password_confirmation" type="password" placeholder="Confirme a Senha" />
                <NSelect
                    v-model:value="form.roles"
                    :options="rolesOptions"
                    multiple
                    filterable
                    clearable
                    placeholder="Selecione os Papéis do Usuário"
                />
            </div>
            <div class="flex justify-end pt-3">
                <Button class="cursor-pointer hover:bg-emerald-500 dark:hover:bg-emerald-700" variant="outline">Salvar</Button>
            </div>
        </form>
    </Modal>
</template>

<style scoped></style>
