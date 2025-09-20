<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Modal from '@/components/ui/modal/Modal.vue';
import courses from '@/routes/courses';
import Coordinator from '@/types/Coordinator';
import Course from '@/types/Course';
import Organization from '@/types/Organization';
import { useForm } from '@inertiajs/vue3';
import { NSelect } from 'naive-ui';
import { computed, watch } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    course: Course | null;
    coordinators_list: Coordinator[];
    organizations_list: Organization[];
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const modalTitle = computed(() => {
    return props.mode === 'create' ? 'Adicionar Curso' : 'Editar Curso';
});

const coordinatorsOptions = computed(() => (props.coordinators_list || []).map((c) => ({ label: c.user.name, value: c.id })));
const organizationsOptions = computed(() => (props.organizations_list || []).map((o) => ({ label: o.name, value: o.id })));

const closeModal = () => emit('update:is-open', false);

const form = useForm<{
    name: string;
    organization_id: number | null;
    coordinator_id: number | null;
}>({
    name: '',
    organization_id: null,
    coordinator_id: null,
});

watch(
    () => [props.course, props.mode, props.isOpen] as const,
    ([course, mode, isOpen]) => {
        if (!isOpen) return;
        if (mode === 'edit' && course) {
            form.name = course.name;
            form.organization_id = course.organization_id;
            form.coordinator_id = course.coordinator_id;
            return;
        }

        form.name = '';
        form.organization_id = null;
        form.coordinator_id = null;
    },
    { immediate: true },
);

function handleSubmit() {
    if (props.mode === 'create') {
        form.post(courses.store().url, {
            onSuccess: () => closeModal(),
        });
        return;
    }

    if (props.course) {
        form.put(courses.update(props.course.id).url, {
            onSuccess: () => closeModal(),
        });
    }
    return;
}
</script>

<template>
    <Modal :id="`${mode}CourseModal`" :is-open="isOpen" :title="modalTitle" size="md" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-3">
                <Input v-model="form.name" placeholder="Digite o Nome do Curso" />
                <NSelect v-model:value="form.organization_id" :options="organizationsOptions" placeholder="Selecione a Organização" />
                <NSelect v-model:value="form.coordinator_id" :options="coordinatorsOptions" placeholder="Selecione o Coordenador" />
            </div>
            <div class="flex justify-end pt-3">
                <Button class="cursor-pointer" variant="outline">Salvar</Button>
            </div>
        </form>
    </Modal>
</template>

<style scoped></style>
