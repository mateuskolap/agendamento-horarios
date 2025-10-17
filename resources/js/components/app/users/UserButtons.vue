<script setup lang="ts">
import CreateUserDialog from '@/components/app/users/CreateUserDialog.vue';
import Role from '@/types/Role';

defineProps<{
    roles_list: Role[];
}>();
</script>

<template>
    <CreateUserDialog :roles_list="roles_list"/>
</template>

<style scoped></style>
