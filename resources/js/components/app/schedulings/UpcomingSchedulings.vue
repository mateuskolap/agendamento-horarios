<script setup lang="ts">
import SchedulingContainerTitle from '@/components/app/schedulings/SchedulingContainerTitle.vue';
import type Scheduling from '@/types/Scheduling';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { computed, ref } from 'vue';
import { Calendar, Clock, User, BookOpen } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import SchedulingApprovalModal from '@/components/app/schedulings/SchedulingApprovalModal.vue';

const props = defineProps<{
    requestedSchedulings: Scheduling[];
    receivedSchedulings: Scheduling[];
}>();

const selectedScheduling = ref<Scheduling | null>(null);
const isModalOpen = ref(false);

// Filtrar apenas os próximos agendamentos (pendentes ou aprovados)
const upcomingRequested = computed(() =>
    props.requestedSchedulings.filter(s =>
        ['pending', 'approved'].includes(s.status)
    ).slice(0, 5)
);

const upcomingReceived = computed(() =>
    props.receivedSchedulings.filter(s =>
        ['pending', 'approved'].includes(s.status)
    ).slice(0, 5)
);

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

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}

function formatTime(time: string): string {
    return time.substring(0, 5);
}

function openSchedulingModal(scheduling: Scheduling) {
    selectedScheduling.value = scheduling;
    isModalOpen.value = true;
}
</script>

<template>
    <div class="rounded-xl bg-accent flex flex-col h-full overflow-hidden">
        <SchedulingContainerTitle title="Próximos Agendamentos"/>

        <Tabs default-value="received" class="flex-1 flex flex-col min-h-0">
            <TabsList class="mx-3 grid w-auto grid-cols-2">
                <TabsTrigger value="received">
                    Recebidos ({{ upcomingReceived.length }})
                </TabsTrigger>
                <TabsTrigger value="requested">
                    Solicitados ({{ upcomingRequested.length }})
                </TabsTrigger>
            </TabsList>

            <TabsContent value="received" class="flex-1 overflow-y-auto px-3 pb-3 mt-3">
                <div v-if="upcomingReceived.length === 0" class="text-center text-muted-foreground py-8">
                    Nenhum agendamento recebido
                </div>
                <div v-else class="space-y-3">
                    <div
                        v-for="scheduling in upcomingReceived"
                        :key="scheduling.id"
                        class="bg-background rounded-lg p-3 border hover:border-primary transition-colors cursor-pointer"
                        @click="openSchedulingModal(scheduling)"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <Badge :variant="getStatusVariant(scheduling.status)">
                                {{ getStatusLabel(scheduling.status) }}
                            </Badge>
                        </div>

                        <h3 class="font-semibold text-sm mb-2 line-clamp-2">
                            {{ scheduling.subject }}
                        </h3>

                        <div class="space-y-1 text-xs text-muted-foreground">
                            <div class="flex items-center gap-2">
                                <User class="w-3 h-3" />
                                <span>{{ scheduling.requester?.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <BookOpen class="w-3 h-3" />
                                <span>{{ scheduling.course?.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Calendar class="w-3 h-3" />
                                <span>{{ formatDate(scheduling.date) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Clock class="w-3 h-3" />
                                <span>{{ formatTime(scheduling.start_time) }} - {{ formatTime(scheduling.end_time) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </TabsContent>

            <TabsContent value="requested" class="flex-1 overflow-y-auto px-3 pb-3 mt-3">
                <div v-if="upcomingRequested.length === 0" class="text-center text-muted-foreground py-8">
                    Nenhum agendamento solicitado
                </div>
                <div v-else class="space-y-3">
                    <div
                        v-for="scheduling in upcomingRequested"
                        :key="scheduling.id"
                        class="bg-background rounded-lg p-3 border hover:border-primary transition-colors cursor-pointer"
                        @click="openSchedulingModal(scheduling)"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <Badge :variant="getStatusVariant(scheduling.status)">
                                {{ getStatusLabel(scheduling.status) }}
                            </Badge>
                        </div>

                        <h3 class="font-semibold text-sm mb-2 line-clamp-2">
                            {{ scheduling.subject }}
                        </h3>

                        <div class="space-y-1 text-xs text-muted-foreground">
                            <div class="flex items-center gap-2">
                                <User class="w-3 h-3" />
                                <span>{{ scheduling.requested?.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <BookOpen class="w-3 h-3" />
                                <span>{{ scheduling.course?.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Calendar class="w-3 h-3" />
                                <span>{{ formatDate(scheduling.date) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Clock class="w-3 h-3" />
                                <span>{{ formatTime(scheduling.start_time) }} - {{ formatTime(scheduling.end_time) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </TabsContent>
        </Tabs>

        <!-- Modal de Aprovação -->
        <SchedulingApprovalModal
            v-model:open="isModalOpen"
            :scheduling="selectedScheduling"
        />
    </div>
</template>

