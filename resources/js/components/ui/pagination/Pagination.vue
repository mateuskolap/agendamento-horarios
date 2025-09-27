<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { NPagination } from 'naive-ui';
import PaginatedData from '@/types/PaginatedData';

const props = defineProps<{
    pagination: PaginatedData<any>;
}>();

const currentPage = computed(() => props.pagination.current_page);
const totalPages = computed(() => props.pagination.last_page);
const pageSize = computed(() => props.pagination.per_page);

function handlePageChange(page: number) {
    if (page === currentPage.value) return;

    const url = new URL(window.location.href);
    url.searchParams.set('page', page.toString());

    router.get(url.toString(), {}, {
        preserveState: true,
        preserveScroll: true
    });
}
</script>

<template>
    <div class="mt-4 flex justify-end">
        <NPagination
            :page="currentPage"
            :page-count="totalPages"
            :page-size="pageSize"
            show-quick-jumper
            :page-slot="5"
            @update:page="handlePageChange"
        />
    </div>
</template>
