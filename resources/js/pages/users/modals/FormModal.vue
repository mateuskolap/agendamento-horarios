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
import { makeFormOptions } from '@/lib/utils';
import FormGroup from '@/components/FormGroup.vue';

const props = defineProps<{
    isOpen: boolean;
    user?: User;
    roles_list: Role[];
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const mode = computed(() => (props.user ? 'edit' : 'create'));
const title = computed(() => (mode.value === 'create' ? 'Adicionar Usuário' : 'Editar Usuário'));

const rolesOptions = computed(() => (props.roles_list || []).map((r) => ({ label: r.name, value: r.id })));

const closeModal = () => emit('update:is-open', false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_ids: [] as number[],
});

watch(
    () => props.user,
    (user) => {
        if (mode.value === 'edit' && user) {
            form.name = user.name;
            form.email = user.email;
            form.password = '';
            form.password_confirmation = '';
            form.role_ids = user.roles.length > 0 ? user.roles.map(role => role.id) : [];
        } else if (mode.value === 'create') {
            form.name = '';
            form.email = '';
            form.password = '';
            form.password_confirmation = '';
            form.role_ids = [];
        }
    },
    { immediate: true },
);

const formOptions = makeFormOptions(() => {
    form.reset();
    closeModal();
});

function handleSubmit() {
    if (mode.value === 'create') {
        form.post(users.store().url, formOptions);
    } else if (mode.value === 'edit' && props.user) {
        form.put(users.update(props.user.id).url, formOptions);
    }
}
</script>

<template>
    <Modal :id="`${mode}UserModal`" :is-open="isOpen" :title="title" size="md" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-3">
                <FormGroup label="Nome" :input-error="form.errors.name" required>
                    <Input v-model="form.name" placeholder="Digite o Nome do Usuário" />
                </FormGroup>
                <FormGroup label="E-mail" :input-error="form.errors.email" required>
                    <Input v-model="form.email" type="email" placeholder="Digite o E-mail" />
                </FormGroup>
                <FormGroup label="Senha" :input-error="form.errors.password" :required="mode === 'create'">
                    <Input v-model="form.password" type="password" :placeholder="mode === 'edit' ? 'Digite a Nova Senha (deixe vazio para manter)' : 'Digite a Senha'" />
                </FormGroup>
                <FormGroup v-if="mode === 'create' || form.password" label="Confirmar Senha" :input-error="form.errors.password_confirmation" :required="mode === 'create' || !!form.password">
                    <Input v-model="form.password_confirmation" type="password" placeholder="Confirme a Senha" />
                </FormGroup>
                <FormGroup label="Papel do Usuário" :input-error="form.errors.role_ids" required>
                    <NSelect
                        v-model:value="form.role_ids"
                        :options="rolesOptions"
                        multiple
                        filterable
                        clearable
                        placeholder="Selecione o Papel do Usuário"
                    />
                </FormGroup>
            </div>
            <div class="flex justify-end pt-3">
                <Button class="cursor-pointer hover:bg-emerald-500 dark:hover:bg-emerald-700" variant="outline">Salvar</Button>
            </div>
        </form>
    </Modal>
</template>
