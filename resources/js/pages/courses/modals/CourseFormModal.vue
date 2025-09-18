<script setup lang="ts">
import Modal from '@/components/ui/modal/Modal.vue';
import Coordinator from '@/types/Coordinator';
import Course from '@/types/Course';
import { computed } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    course: Course | null;
    coordinators_list: Coordinator[];
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const modalTitle = computed(() => {
    return props.mode === 'create' ? 'Adicionar Curso' : 'Editar Curso';
});

const closeModal = () => emit('update:is-open', false);
</script>

<template>
    <Modal :id="`${mode}CourseModal`" :title="modalTitle" :is-open="isOpen" size="md" @update:is-open="closeModal">
    </Modal>
</template>

<style scoped></style>
