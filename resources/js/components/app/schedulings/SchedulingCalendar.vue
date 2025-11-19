<script setup lang="ts">
import SchedulingContainerTitle from '@/components/app/schedulings/SchedulingContainerTitle.vue';
import type Scheduling from '@/types/Scheduling';
import Calendar from '@/components/ui/calendar/Calendar.vue';
import { ref, computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Clock, User, BookOpen } from 'lucide-vue-next';
import SchedulingApprovalModal from '@/components/app/schedulings/SchedulingApprovalModal.vue';

const props = defineProps<{
    requestedSchedulings: Scheduling[];
    receivedSchedulings: Scheduling[];
}>();

const selectedDate = ref<Date>(new Date());
const selectedScheduling = ref<Scheduling | null>(null);
const isModalOpen = ref(false);

const allSchedulings = computed(() => [
    ...props.requestedSchedulings,
    ...props.receivedSchedulings,
]);

const schedulingsForSelectedDate = computed(() => {
    const dateStr = selectedDate.value.toISOString().split('T')[0];
    return allSchedulings.value.filter(s => s.date === dateStr);
});

function getStatusLabel(status: string): string {
    const labels: Record<string, string> = {
        pending: 'Pendente',
        approved: 'Aprovado',
        rejected: 'Rejeitado',
        cancelled: 'Cancelado',
    };
    return labels[status] || status;
}

function getStatusVariant(status: string): 'default' | 'secondary' | 'destructive' | 'outline' {
    const variants: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
        pending: 'secondary',
        approved: 'default',
        rejected: 'destructive',
        cancelled: 'outline',
    };
    return variants[status] || 'default';
}

function formatTime(time: string): string {
    return time.substring(0, 5);
}

function isRequested(scheduling: Scheduling): boolean {
    return props.requestedSchedulings.some(s => s.id === scheduling.id);
}

function openSchedulingModal(scheduling: Scheduling) {
    selectedScheduling.value = scheduling;
    isModalOpen.value = true;
}
</script>

<template>
    <div class="rounded-xl bg-accent flex flex-col h-full overflow-hidden">
        <SchedulingContainerTitle title="Calendário"/>

        <div class="flex-1 overflow-y-auto p-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="flex justify-center items-start">
                    <Calendar
                        v-model="selectedDate"
                        class="rounded-md border shadow bg-background"
                    />
                </div>

                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold mb-3">
                        Agendamentos em {{ selectedDate.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' }) }}
                    </h3>

                    <div v-if="schedulingsForSelectedDate.length === 0" class="text-center text-muted-foreground py-8">
                        Nenhum agendamento neste dia
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="scheduling in schedulingsForSelectedDate"
                            :key="scheduling.id"
                            class="bg-background rounded-lg p-3 border hover:border-primary transition-colors cursor-pointer"
                            @click="openSchedulingModal(scheduling)"
                        >
                            <div class="flex items-start justify-between mb-2">
                                <Badge :variant="getStatusVariant(scheduling.status)">
                                    {{ getStatusLabel(scheduling.status) }}
                                </Badge>
                                <Badge variant="outline" class="ml-2">
                                    {{ isRequested(scheduling) ? 'Solicitado' : 'Recebido' }}
                                </Badge>
                            </div>

                            <h4 class="font-semibold text-sm mb-2 line-clamp-2">
                                {{ scheduling.subject }}
                            </h4>

                            <div class="space-y-1 text-xs text-muted-foreground">
                                <div class="flex items-center gap-2">
                                    <User class="w-3 h-3" />
                                    <span>
                                        {{ isRequested(scheduling) ? scheduling.requested?.name : scheduling.requester?.name }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <BookOpen class="w-3 h-3" />
                                    <span>{{ scheduling.course?.name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Clock class="w-3 h-3" />
                                    <span>{{ formatTime(scheduling.start_time) }} - {{ formatTime(scheduling.end_time) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Aprovação -->
        <SchedulingApprovalModal
            v-model:open="isModalOpen"
            :scheduling="selectedScheduling"
        />
    </div>
</template>

