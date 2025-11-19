<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { usePermission } from '@/composables/usePermissions';
import { useForm } from '@inertiajs/vue3';
import { Pencil, Card } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import Role from '@/types/Role';
import users from '@/routes/users';
import { User } from '@/types';

const props = defineProps<{
    user: User;
    roles_list: Role[];
}>();

const canUpdate = usePermission()('users.update');

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    password: '',
    password_confirmation: '',
    role_ids: props.user.roles.map((r) => r.id) || ([] as number[]),
});

function submit() {
    form.put(users.update(props.user.id).url, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button v-if="canUpdate" variant="ghost" size="sm" class="cursor-pointer">
                <Pencil class="h-4 w-4" />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Editar Usuário</DialogTitle>
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
                        <Input v-model="form.password" type="password" placeholder="Digite a Nova Senha (deixe vazio para manter)" />
                    </FormGroup>
                    <FormGroup v-if="form.password" label="Confirmar Senha" :input-error="form.errors.password_confirmation" required>
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
                    <DialogClose as-child>
                        <Button type="submit" :disabled="form.processing">
                            <Card />
                            Salvar
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
