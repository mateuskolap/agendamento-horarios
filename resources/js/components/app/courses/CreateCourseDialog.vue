<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { usePermission } from '@/composables/usePermissions';
import courses from '@/routes/courses';
import { User } from '@/types';
import Organization from '@/types/Organization';
import { useForm } from '@inertiajs/vue3';
import { Add, Card } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import { ref } from 'vue';

defineProps<{
    coordinators_list: User[];
    organizations_list: Organization[];
}>();

const can = usePermission();
const isOpen = ref(false);

const form = useForm({
    name: '',
    organization_id: null as number | null,
    coordinator_id: null as number | null,
});

function submit() {
    form.post(courses.store().url, {
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
                <DialogTitle>Adicionar Curso</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit">
                <div class="grid gap-4 py-4">
                    <FormGroup label="Nome" :input-error="form.errors.name" required>
                        <Input v-model="form.name" placeholder="Digite o Nome do Curso" />
                    </FormGroup>
                    <FormGroup label="Organização" :input-error="form.errors.organization_id" required>
                        <NSelect
                            v-model:value="form.organization_id"
                            :options="organizations_list as any"
                            value-field="id"
                            label-field="name"
                            filterable
                            clearable
                            placeholder="Selecione a Organização"
                            :to="false"
                        />
                    </FormGroup>
                    <FormGroup label="Coordenador" :input-error="form.errors.coordinator_id" required>
                        <NSelect
                            v-model:value="form.coordinator_id"
                            :options="coordinators_list as any"
                            value-field="id"
                            label-field="name"
                            filterable
                            clearable
                            placeholder="Selecione o Coordenador"
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
