<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import users from '@/routes/users';
import { User } from '@/types';
import Organization from '@/types/Organization';
import Role from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { Card } from '@vicons/ionicons5';
import { Link2 } from 'lucide-vue-next';
import { NSelect, SelectGroupOption, SelectOption } from 'naive-ui';
import { computed, ref } from 'vue';

const props = defineProps<{
    user: User;
    role: Role;
    organizations_list: Organization[];
}>();

const isOpen = ref(false);

const courseOptions = computed((): Array<SelectOption | SelectGroupOption> => {
    return props.organizations_list.map((organization) => ({
        type: 'group' as const,
        label: organization.name,
        key: organization.id,
        children: organization.courses.map((course) => ({
            label: course.name,
            value: course.id,
        })),
    }));
});

const form = useForm({
    role_id: props.role.id,
    course_ids: [] as number[],
});

function submit() {
    form.post(users.courses.store(props.user.id).url, {
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
                <Link2 />
                Vincular Cursos
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Vincular Cursos</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit">
                <div class="grid gap-3 py-4">
                    <FormGroup label="Cursos" :input-error="form.errors.course_ids" required>
                        <NSelect
                            v-model:value="form.course_ids"
                            :options="courseOptions"
                            multiple
                            clearable
                            filterable
                            placeholder="Selecione os Cursos"
                            :to="false"
                        />
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
