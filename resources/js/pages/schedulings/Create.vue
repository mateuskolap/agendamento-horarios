<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import Calendar from '@/components/ui/calendar/Calendar.vue';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { schedulings as schedulingsList } from '@/routes';
import courses from '@/routes/courses';
import schedulings from '@/routes/schedulings';
import users from '@/routes/users';
import type { BreadcrumbItem, User } from '@/types';
import Course from '@/types/Course';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { NSelect } from 'naive-ui';
import { computed, ref, watch } from 'vue';

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
const availableTimes = ref<string[]>([]);
const isLoadingTimes = ref(false);

function formatDate(date: Date | string): string {
    if (!date) return '';
    if (typeof date === 'string') return date;

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

watch(
    () => form.requested_id,
    async (requestedId) => {
        availableCourses.value = [];
        form.course_id = null;

        if (!requestedId) return;

        isLoadingCourses.value = true;
        try {
            const response = await axios.get(courses.availableByRequested().url, {
                params: { requested_id: requestedId },
            });
            availableCourses.value = response.data;
        } catch (error) {
            console.error('Erro ao buscar cursos:', error);
        } finally {
            isLoadingCourses.value = false;
        }
    },
);

watch(
    () => [form.requested_id, form.date] as const,
    async ([requestedId, date]) => {
        availableTimes.value = [];
        form.start_time = '';
        form.end_time = '';

        if (!requestedId || !date) return;

        isLoadingTimes.value = true;
        try {
            const formattedDate = formatDate(date);
            const response = await axios.get(users.available_times.get(requestedId).url, {
                params: { date: formattedDate },
            });

            if (Array.isArray(response.data)) {
                availableTimes.value = response.data;
            }
        } catch (error) {
            console.error('Erro ao buscar horários:', error);
        } finally {
            isLoadingTimes.value = false;
        }
    },
);

const startTimeOptions = computed(() => {
    return availableTimes.value.map((time) => ({
        label: time.substring(0, 5),
        value: time,
    }));
});

const endTimeOptions = computed(() => {
    if (!form.start_time) return [];

    const startIndex = availableTimes.value.indexOf(form.start_time);
    if (startIndex === -1) return [];

    const nextTimes = availableTimes.value.slice(startIndex + 1);

    if (nextTimes.length === 0) {
        const [hours, minutes] = form.start_time.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, minutes + 30, 0);
        const endTime = date.toTimeString().substring(0, 8);

        return [
            {
                label: endTime.substring(0, 5),
                value: endTime,
            },
        ];
    }

    return nextTimes.map((time) => ({
        label: time.substring(0, 5),
        value: time,
    }));
});

watch(
    () => form.start_time,
    () => {
        form.end_time = '';
    },
);

function submit() {
    const formData = {
        requested_id: form.requested_id,
        course_id: form.course_id,
        date: formatDate(form.date),
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
                        <Calendar v-model="form.date" :min-date="new Date()" :disabled="form.processing" />
                    </FormGroup>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <FormGroup label="Hora de Início" :input-error="form.errors.start_time" required>
                            <NSelect
                                v-model:value="form.start_time"
                                :options="startTimeOptions"
                                placeholder="Selecione o horário de início"
                                :disabled="form.processing || !form.requested_id || !form.date || isLoadingTimes"
                                :loading="isLoadingTimes"
                                :to="false"
                                clearable
                            />
                        </FormGroup>

                        <FormGroup label="Hora de Término" :input-error="form.errors.end_time" required>
                            <NSelect
                                v-model:value="form.end_time"
                                :options="endTimeOptions"
                                placeholder="Selecione o horário de término"
                                :disabled="form.processing || !form.start_time"
                                :to="false"
                                clearable
                            />
                        </FormGroup>
                    </div>

                    <FormGroup label="Assunto" :input-error="form.errors.subject" required>
                        <Textarea v-model="form.subject" placeholder="Descreva o assunto da reunião" :disabled="form.processing" :rows="4" />
                    </FormGroup>

                    <div class="mt-4 flex justify-end gap-3">
                        <Button type="button" variant="outline" @click="$inertia.visit(schedulingsList().url)" :disabled="form.processing">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Enviando...' : 'Solicitar Agendamento' }}
                        </Button>
                    </div>
                </form>
            </section>
        </IndexLayout>
    </AppLayout>
</template>
