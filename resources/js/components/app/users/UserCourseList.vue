<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import users from '@/routes/users';
import Course from '@/types/Course';
import Role from '@/types/Role';
import { router } from '@inertiajs/vue3';
import { Link2Off } from 'lucide-vue-next';

const props = defineProps<{
    user_courses: Course[];
    selected_role: Role;
    can_manage: number;
}>();

const { confirmDelete, showDeleteSuccess } = useConfirmDelete();

const handleDetachCourse = async (course: Course) => {
    const confirmed = await confirmDelete('Desvincular curso?', `Tem certeza que deseja desvincular o curso "${course.name}" deste usuário?`);

    if (confirmed) {
        const deleteData: any = {};
        deleteData.role_id = props.selected_role.id;

        router.delete(users.courses.destroy({ user: course.pivot.user_id, course: course.id }).url, {
            data: deleteData,
            onSuccess: () => showDeleteSuccess('Curso desvinculado!', 'O curso foi desvinculado do usuário com sucesso.'),
        });
    }
};
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>Organização</TableHead>
                <TableHead>Curso</TableHead>
                <TableHead v-if="can_manage">Ações</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="course in user_courses" :key="course.id">
                <TableCell>{{ course.organization.name }}</TableCell>
                <TableCell>{{ course.name }}</TableCell>
                <TableCell v-if="can_manage">
                    <Button variant="ghost" size="sm" class="cursor-pointer" @click="() => handleDetachCourse(course)">
                        <Link2Off class="text-red-500 dark:text-red-700" />
                    </Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
