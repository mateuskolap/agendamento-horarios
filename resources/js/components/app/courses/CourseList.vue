<script setup lang="ts">
import UpdateCourseDialog from '@/components/app/courses/UpdateCourseDialog.vue';
import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemHeader, ItemTitle } from '@/components/ui/item';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirmDelete } from '@/composables/useConfirmDelete';
import { usePermission } from '@/composables/usePermissions';
import courses from '@/routes/courses';
import Course from '@/types/Course';
import { router } from '@inertiajs/vue3';
import { BusinessOutline, PersonOutline, Trash } from '@vicons/ionicons5';
import { User } from '@/types';
import Organization from '@/types/Organization';

defineProps<{
    courses_list: Course[];
    coordinators_list: User[];
    organizations_list: Organization[];
}>();

const { confirmDelete, showDeleteSuccess } = useConfirmDelete();

const canEdit = usePermission()('courses.update');
const canDelete = usePermission()('courses.delete');

const handleDeleteCourse = async (course: Course) => {
    const confirmed = await confirmDelete('Excluir curso?', `Tem certeza que deseja excluir o curso "${course.name}"?`);

    if (confirmed) {
        router.delete(courses.destroy(course.id).url, {
            onSuccess: () => showDeleteSuccess('Curso excluído!', 'O curso foi excluído com sucesso.'),
        });
    }
};
</script>

<template>
    <div class="block md:hidden space-y-4">
        <Item v-for="course in courses_list" :key="course.id" variant="outline">
            <ItemHeader class="border-b-2">
                <ItemTitle>{{ course.name }}</ItemTitle>
                <ItemActions>
                    <UpdateCourseDialog :course="course" :coordinators_list="coordinators_list" :organizations_list="organizations_list" />
                    <Button v-if="canDelete" variant="ghost" size="sm" class="cursor-pointer" @click="handleDeleteCourse(course)">
                        <Trash class="h-4 w-4 text-red-500 dark:text-red-700" />
                    </Button>
                </ItemActions>
            </ItemHeader>
            <ItemContent>
                <div class="flex items-center gap-3">
                    <BusinessOutline class="h-5 w-5 flex-shrink-0 text-muted-foreground" />
                    <div>
                        <span class="text-xs font-semibold text-muted-foreground uppercase"> Organização </span>
                        <p class="font-medium text-foreground">
                            {{ course.organization.name }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <PersonOutline class="h-5 w-5 flex-shrink-0 text-muted-foreground" />
                    <div>
                        <span class="text-xs font-semibold text-muted-foreground uppercase"> Coordenador </span>
                        <p class="font-medium text-foreground">
                            {{ course.coordinator.name }}
                        </p>
                    </div>
                </div>
            </ItemContent>
        </Item>
    </div>

    <div class="hidden md:block">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Organização</TableHead>
                    <TableHead>Curso</TableHead>
                    <TableHead>Coordenador</TableHead>
                    <TableHead v-if="canEdit || canDelete">Ações</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="course in courses_list" :key="course.id">
                    <TableCell>{{ course.id }}</TableCell>
                    <TableCell>{{ course.organization.name }}</TableCell>
                    <TableCell>{{ course.name }}</TableCell>
                    <TableCell>{{ course.coordinator.name }}</TableCell>
                    <TableCell v-if="canEdit || canDelete">
                        <UpdateCourseDialog :course="course" :coordinators_list="coordinators_list" :organizations_list="organizations_list" />
                        <Button v-if="canDelete" variant="ghost" size="sm" class="cursor-pointer" @click="handleDeleteCourse(course)">
                            <Trash class="h-4 w-4 text-red-500 dark:text-red-700" />
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
