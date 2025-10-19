<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { usePermission } from '@/composables/usePermissions';
import users from '@/routes/users';
import Role from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { Add, Card } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import { ref } from 'vue';

defineProps<{
    roles_list: Role[];
}>();

const can = usePermission();
const isOpen = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_ids: [] as number[],
});

function submit() {
    form.post(users.store().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            isOpen.value = false;
        },
    });
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button v-if="can('courses.create')" class="cursor-pointer hover:bg-green-500 hover:dark:bg-green-700" variant="outline">
                <Add />
                Adicionar
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Adicionar Usuário</DialogTitle>
                <DialogDescription />
            </DialogHeader>
            <form @submit.prevent="submit">
                <div class="grid gap-3 py-4">
                    <FormGroup label="Nome" :input-error="form.errors.name" required>
                        <Input v-model="form.name" placeholder="Digite o Nome do Usuário" />
                    </FormGroup>
                    <FormGroup label="E-mail" :input-error="form.errors.email" required>
                        <Input v-model="form.email" type="email" placeholder="Digite o E-mail" />
                    </FormGroup>
                    <FormGroup label="Senha" :input-error="form.errors.password">
                        <Input v-model="form.password" type="password" placeholder="Digite a Senha" />
                    </FormGroup>
                    <FormGroup label="Confirmar Senha" :input-error="form.errors.password_confirmation" required>
                        <Input v-model="form.password_confirmation" type="password" placeholder="Confirme a Senha" />
                    </FormGroup>
                    <FormGroup label="Papel do Usuário" :input-error="form.errors.role_ids" required>
                        <NSelect
                            v-model:value="form.role_ids"
                            :options="roles_list as any"
                            value-field="id"
                            label-field="name"
                            multiple
                            filterable
                            clearable
                            placeholder="Selecione o Papel do Usuário"
                            :to="false"
                        />
                    </FormGroup>
                </div>
                <DialogFooter>
                    <Button type="submit" :disabled="form.processing">
                        <Card />
                        Salvar
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
