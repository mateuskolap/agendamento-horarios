<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Modal from '@/components/ui/modal/Modal.vue';
import availabilities from '@/routes/availabilities';
import Weekday from '@/types/Weekday';
import { useForm, usePage } from '@inertiajs/vue3';
import { Card } from '@vicons/ionicons5';

const page = usePage();
const user = page.props.auth.user;

const props = defineProps<{
    isOpen: boolean;
    weekday?: Weekday;
}>();

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();
const closeModal = () => emit('update:is-open', false);

const form = useForm({
    user_id: user.id,
    weekday: props.weekday?.value,
    start_time: '',
    end_time: '',
});

function handleSubmit() {
    form.post(availabilities.store().url, {
        onSuccess: () => {
            form.reset();
            closeModal();
        },
    });
}
</script>

<template>
    <Modal id="AvailabilitiesHoursAddModal" :title="weekday?.full || ''" size="md" :is-open="isOpen" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <div class="pb-3">
                <FormGroup label="Horário inicial" :input-error="form.errors.start_time" required>
                    <Input v-model="form.start_time" type="time" step="1800" />
                </FormGroup>
                <FormGroup label="Horário final" :input-error="form.errors.end_time" required>
                    <Input v-model="form.end_time" type="time" step="1800" />
                </FormGroup>
            </div>

            <div class="flex justify-end">
                <Button class="cursor-pointer">
                    <Card />
                    Salvar
                </Button>
            </div>
        </form>
    </Modal>
</template>
