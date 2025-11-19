<script setup lang="ts">
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import FormGroup from '@/components/FormGroup.vue';
import { Badge } from '@/components/ui/badge';
import type Scheduling from '@/types/Scheduling';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Calendar, Clock, User, BookOpen, CheckCircle, XCircle } from 'lucide-vue-next';
import schedulings from '@/routes/schedulings';
import { usePermission } from '@/composables/usePermissions';

const props = defineProps<{
    open: boolean;
    scheduling: Scheduling | null;
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const can = usePermission();

const form = useForm({
    status: '',
    response_message: '',
});

const isApproving = ref(false);

const canApprove = computed(() => {
    return props.scheduling?.status === 'pending';
});

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function formatTime(time: string): string {
    return time.substring(0, 5);
}

function handleApprove() {
    isApproving.value = true;
    form.status = 'approved';
    submitForm();
}

function handleReject() {
    isApproving.value = false;
    form.status = 'rejected';
    submitForm();
}

function submitForm() {
    if (!props.scheduling) return;

    form.patch(schedulings.updateStatus(props.scheduling.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('update:open', false);
            form.reset();
        },
        onError: () => {},
    });
}

function closeModal() {
    emit('update:open', false);
    form.reset();
}
</script>

<template>
    <Dialog :open="open" @update:open="closeModal">
        <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Detalhes da Solicitação de Agendamento</DialogTitle>
                <DialogDescription>
                    Revise os detalhes e aprove ou rejeite a solicitação
                </DialogDescription>
            </DialogHeader>

            <div v-if="scheduling" class="space-y-4 py-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium">Status:</span>
                    <Badge
                        :variant="
                            scheduling.status === 'pending' ? 'secondary' :
                            scheduling.status === 'approved' ? 'default' :
                            scheduling.status === 'rejected' ? 'destructive' : 'outline'
                        "
                    >
                        {{
                            scheduling.status === 'pending' ? 'Pendente' :
                            scheduling.status === 'approved' ? 'Aprovado' :
                            scheduling.status === 'rejected' ? 'Rejeitado' : 'Cancelado'
                        }}
                    </Badge>
                </div>

                <div class="grid gap-4 p-4 bg-accent rounded-lg">
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <User class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium">Solicitante</p>
                                <p class="text-sm text-muted-foreground">{{ scheduling.requester?.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ scheduling.requester?.email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <BookOpen class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium">Curso</p>
                                <p class="text-sm text-muted-foreground">{{ scheduling.course?.name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <Calendar class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium">Data</p>
                                <p class="text-sm text-muted-foreground">{{ formatDate(scheduling.date) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <Clock class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium">Horário</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ formatTime(scheduling.start_time) }} - {{ formatTime(scheduling.end_time) }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 border-t">
                            <p class="text-sm font-medium mb-2">Assunto</p>
                            <p class="text-sm text-muted-foreground">{{ scheduling.subject }}</p>
                        </div>
                    </div>
                </div>

                <FormGroup
                    v-if="canApprove && can('schedulings.update-status')"
                    label="Mensagem de Resposta (Opcional)"
                    :input-error="form.errors.response_message"
                >
                    <Textarea
                        v-model="form.response_message"
                        placeholder="Adicione uma mensagem para o solicitante..."
                        :rows="3"
                        :disabled="form.processing"
                    />
                </FormGroup>

                <div v-if="Object.keys(form.errors).length > 0" class="text-sm text-destructive">
                    {{ form.errors.status || 'Ocorreu um erro ao processar a solicitação' }}
                </div>
            </div>

            <DialogFooter class="gap-2">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeModal"
                    :disabled="form.processing"
                >
                    Cancelar
                </Button>

                <Button
                    v-if="canApprove && can('schedulings.update-status')"
                    type="button"
                    variant="destructive"
                    @click="handleReject"
                    :disabled="form.processing"
                >
                    <XCircle class="w-4 h-4 mr-2" />
                    {{ form.processing && !isApproving ? 'Rejeitando...' : 'Rejeitar' }}
                </Button>

                <Button
                    v-if="canApprove && can('schedulings.update-status')"
                    type="button"
                    @click="handleApprove"
                    :disabled="form.processing"
                >
                    <CheckCircle class="w-4 h-4 mr-2" />
                    {{ form.processing && isApproving ? 'Aprovando...' : 'Aprovar' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
