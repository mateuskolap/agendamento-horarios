<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, DialogClose } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { usePermission } from '@/composables/usePermissions';
import courses from '@/routes/courses';
import { User } from '@/types';
import Organization from '@/types/Organization';
import { useForm } from '@inertiajs/vue3';
import { Card, Pencil } from '@vicons/ionicons5';
import { NSelect } from 'naive-ui';
import Course from '@/types/Course';

const props = defineProps<{
    course: Course;
    coordinators_list: User[];
    organizations_list: Organization[];
}>();

const canEdit = usePermission()('courses.update');

const form = useForm({
    name: props.course.name || '',
    organization_id: props.course.organization_id || null as number | null,
    coordinator_id: props.course.coordinator_id || null as number | null,
});

function submit() {
    form.put(courses.update(props.course.id).url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button v-if="canEdit" variant="ghost" size="sm" class="cursor-pointer">
                <Pencil class="h-4 w-4" />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Adicionar Curso</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit">
                <div class="grid gap-4 py-4">
                    <FormGroup label="Nome" :input-error="form.errors.name">
                        <Input v-model="form.name" placeholder="Digite o Nome do Curso" />
                    </FormGroup>
                    <FormGroup label="Organização" :input-error="form.errors.organization_id">
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
                    <FormGroup label="Coordenador" :input-error="form.errors.coordinator_id">
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
