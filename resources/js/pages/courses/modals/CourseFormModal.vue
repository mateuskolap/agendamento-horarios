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
import { makeFormOptions } from '@/types/helpers';

const props = defineProps<{
    isOpen: boolean;
    course?: Course;
    coordinators_list: Coordinator[];
    organizations_list: Organization[];
}>();

const coordinatorsOptions = computed(() => (props.coordinators_list || []).map((c) => ({ label: c.user.name, value: c.id })));
const organizationsOptions = computed(() => (props.organizations_list || []).map((o) => ({ label: o.name, value: o.id })));

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const mode = computed(() => (props.course ? 'edit' : 'create'));
const title = computed(() => (mode.value === 'create' ? 'Adicionar Curso' : 'Editar Curso'));

const closeModal = () => emit('update:is-open', false);

const form = useForm({
    name: '',
    organization_id: null as number | null,
    coordinator_id: null as number | null,
});

watch(
    () => props.course,
    (course) => {
        if (mode.value === 'edit' && course) {
            form.name = course.name;
            form.organization_id = course.organization_id;
            form.coordinator_id = course.coordinator_id;
        } else if (mode.value === 'create') {
            form.name = '';
            form.organization_id = null;
            form.coordinator_id = null;
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
        form.post(courses.store().url, formOptions);
    } else if (mode.value === 'edit' && props.course) {
        form.put(courses.update(props.course.id).url, formOptions);
    }
}
</script>

<template>
    <Modal :id="`${mode}CourseModal`" :is-open="isOpen" :title="title" size="md" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-3">
                <Input v-model="form.name" placeholder="Digite o Nome do Curso" />
                <NSelect
                    v-model:value="form.organization_id"
                    :options="organizationsOptions"
                    filterable
                    clearable
                    placeholder="Selecione a Organização"
                />
                <NSelect
                    v-model:value="form.coordinator_id"
                    :options="coordinatorsOptions"
                    filterable
                    clearable
                    placeholder="Selecione o Coordenador"
                />
            </div>
            <div class="flex justify-end pt-3">
                <Button class="cursor-pointer hover:bg-emerald-500 dark:hover:bg-emerald-700" variant="outline">Salvar</Button>
            </div>
        </form>
    </Modal>
</template>

<style scoped></style>
