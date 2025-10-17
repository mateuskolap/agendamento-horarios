<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import availabilities from '@/routes/availabilities';
import Weekday from '@/types/Weekday';
import { useForm, usePage } from '@inertiajs/vue3';
import { Card, Pencil } from '@vicons/ionicons5';
import { ref } from 'vue';

const page = usePage();
const user = page.props.auth.user;
const isOpen = ref<boolean>(false);

const props = defineProps<{
    weekday: Weekday;
}>();

const form = useForm({
    user_id: user.id,
    weekday: props.weekday.value,
    start_time: '',
    end_time: '',
});

function submit() {
    form.post(availabilities.store().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            isOpen.value = false;
        },
    });
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button variant="outline" class="cursor-pointer">
                <Pencil />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Adicionar Horários</DialogTitle>
                <DialogDescription>Escolha horários de 30 em 30 minutos.</DialogDescription>
            </DialogHeader>
            <form @submit.prevent="submit">
                <div class="grid gap-3 py-4">
                    <FormGroup label="Horário inicial" :input-error="form.errors.start_time" required>
                        <Input v-model="form.start_time" type="time" step="1800" />
                    </FormGroup>
                    <FormGroup label="Horário final" :input-error="form.errors.end_time" required>
                        <Input v-model="form.end_time" type="time" step="1800" />
                    </FormGroup>
                </div>
                <DialogFooter>
                    <Button class="cursor-pointer">
                        <Card />
                        Salvar
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
