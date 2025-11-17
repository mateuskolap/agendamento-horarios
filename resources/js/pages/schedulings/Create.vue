<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings as schedulingsList } from '@/routes';
import courses from '@/routes/courses';
import schedulings from '@/routes/schedulings';
import type { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';
import Calendar from '@/components/ui/calendar/Calendar.vue';
import TimeInput from '@/components/ui/input/TimeInput.vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { NSelect } from 'naive-ui';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: schedulingsList().url,
    },
    {
        title: 'Solicitação',
        href: schedulings.create().url,
    },
];

defineProps<{
    educators: User[];
}>();

const form = useForm({
    requested_id: null as number | null,
    course_id: null as number | null,
    date: '',
    start_time: '',
    end_time: '',
    subject: '',
});

const availableCourses = ref<Course[]>([]);
const isLoadingCourses = ref(false);

watch(
    () => form.requested_id,
    async (requested_id) => {
        if (requested_id) {
            isLoadingCourses.value = true;
            form.course_id = null;
            availableCourses.value = [];

            axios
                .get(courses.availableByRequested().url, {
                    params: {
                        requested_id: requested_id,
                    },
                })
                .then((response) => {
                    availableCourses.value = response.data;
                })
                .finally(() => {
                    isLoadingCourses.value = false;
                });
        } else {
            availableCourses.value = [];
            form.course_id = null;
        }
    },
);

function submit() {
    const dateObj = new Date(form.date);
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const day = String(dateObj.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    const formData = {
        requested_id: form.requested_id,
        course_id: form.course_id,
        date: formattedDate,
        start_time: form.start_time,
        end_time: form.end_time,
        subject: form.subject,
    };

    form.transform(() => formData).post(schedulings.store().url);
}
</script>

<template>
    <Head title="Solicitar Agendamento" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <IndexLayout title="Solicitar Agendamento">
            <section class="max-w-2xl">
                <form @submit.prevent="submit" class="flex flex-col gap-4">
                    <FormGroup label="Educador" :input-error="form.errors.requested_id" required>
                        <NSelect
                            v-model:value="form.requested_id"
                            :options="educators as any"
                            value-field="id"
                            label-field="name"
                            filterable
                            clearable
                            placeholder="Por favor selecione"
                            :to="false"
                        />
                    </FormGroup>

                    <FormGroup label="Curso" :input-error="form.errors.course_id" required>
                        <NSelect
                            v-model:value="form.course_id"
                            :options="availableCourses as any"
                            value-field="id"
                            label-field="name"
                            filterable
                            clearable
                            placeholder="Por favor selecione"
                            :disabled="!form.requested_id || isLoadingCourses"
                            :loading="isLoadingCourses"
                            :to="false"
                        />
                    </FormGroup>

                    <FormGroup label="Data" :input-error="form.errors.date" required>
                        <Calendar
                            v-model="form.date"
                            :min-date="new Date()"
                            :disabled="form.processing"
                        />
                    </FormGroup>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormGroup label="Hora de Início" :input-error="form.errors.start_time" required>
                            <TimeInput
                                v-model="form.start_time"
                                :disabled="form.processing"
                            />
                        </FormGroup>

                        <FormGroup label="Hora de Término" :input-error="form.errors.end_time" required>
                            <TimeInput
                                v-model="form.end_time"
                                :disabled="form.processing"
                            />
                        </FormGroup>
                    </div>

                    <FormGroup label="Assunto" :input-error="form.errors.subject" required>
                        <Textarea
                            v-model="form.subject"
                            placeholder="Descreva o assunto da reunião"
                            :disabled="form.processing"
                            :rows="4"
                        />
                    </FormGroup>

                    <div class="flex gap-3 justify-end mt-4">
                        <Button
                            type="button"
                            variant="outline"
                            @click="$inertia.visit(schedulingsList().url)"
                            :disabled="form.processing"
                        >
                            Cancelar
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Enviando...' : 'Solicitar Agendamento' }}
                        </Button>
                    </div>
                </form>
            </section>
        </IndexLayout>
    </AppLayout>
</template>
