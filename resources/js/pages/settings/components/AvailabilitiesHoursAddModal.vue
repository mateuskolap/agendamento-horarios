<script setup lang="ts">
import Modal from '@/components/ui/modal/Modal.vue';
import Weekday from '@/types/Weekday';

defineProps<{
    isOpen: boolean;
    weekday: Weekday;
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const closeModal = () => emit('update:is-open', false);
</script>

<template>
    <Modal id="AvailabilitiesHoursAddModal" :title="weekday.full" size="md" is-open="">

    </Modal>
</template>
