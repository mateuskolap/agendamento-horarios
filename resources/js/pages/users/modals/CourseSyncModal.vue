<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import Modal from '@/components/ui/modal/Modal.vue';
import { makeFormOptions } from '@/lib/utils';
import users from '@/routes/users';
import { User } from '@/types';
import Organization from '@/types/Organization';
import Role from '@/types/Role';
import { useForm } from '@inertiajs/vue3';
import { NSelect } from 'naive-ui';
import type { SelectGroupOption, SelectOption } from 'naive-ui';
import { computed } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    user: User;
    role: Role;
    organizations_list: Organization[];
}>();

const courseOptions = computed((): Array<SelectOption | SelectGroupOption> => {
    return props.organizations_list.map(organization => ({
        type: 'group' as const,
        label: organization.name,
        key: organization.id,
        children: organization.courses.map(course => ({
            label: course.name,
            value: course.id
        }))
    }));
});

const emit = defineEmits<{
    (e: 'update:is-open', value: boolean): void;
}>();

const closeModal = () => emit('update:is-open', false);

const form = useForm({
    role_id: props.role.id,
    course_ids: [] as number[],
});

const formOptions = makeFormOptions(() => {
    form.reset();
    closeModal();
});

function handleSubmit() {
    if (!props.user?.id) return;
    form.post(users.courses.store(props.user.id).url, formOptions);
}
</script>

<template>
    <Modal :is-open="isOpen" id="CourseSyncModal" title="Sincronizar Cursos" size="md" @update:is-open="closeModal">
        <form @submit.prevent="handleSubmit">
            <FormGroup label="Cursos" :input-error="form.errors.course_ids" required>
                <NSelect
                    v-model:value="form.course_ids"
                    :options="courseOptions"
                    multiple
                    clearable
                    filterable
                    placeholder="Selecione os Cursos"
                />
            </FormGroup>

            <div class="flex justify-end pt-3">
                <Button class="cursor-pointer hover:bg-emerald-500 dark:hover:bg-emerald-700" variant="outline">Salvar</Button>
            </div>
        </form>
    </Modal>
</template>
