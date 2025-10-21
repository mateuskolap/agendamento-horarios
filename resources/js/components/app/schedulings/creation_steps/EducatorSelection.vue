<script setup lang="ts">
/* eslint-disable vue/no-mutating-props */
import FormGroup from '@/components/FormGroup.vue';
import { translateArray } from '@/plugins/translate';
import Role from '@/types/Role';
import { InertiaForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    form: InertiaForm<any>;
    roles: Role[];
}>();

const rolesListPtBr = computed(() => translateArray(props.roles, ['name']));
</script>

<template>
    <div class="grid grid-cols-2">
        <FormGroup label="Papel do Educador" :input-error="form.errors.educator_role_id" required>
            <NSelect
                v-model:value="form.educator_role_id"
                :options="rolesListPtBr as any"
                value-field="id"
                label-field="name"
                placeholder="Selecione o papel para o agendamento"
                clearable
            />
        </FormGroup>
    </div>
</template>
