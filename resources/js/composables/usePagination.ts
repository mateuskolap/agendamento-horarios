import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface UsePaginationOptions {
    route: string;
    params?: Record<string, any>;
    preserveState?: boolean;
    preserveScroll?: boolean;
}

export function usePagination({ route, params = {}, preserveState = true, preserveScroll = true }: UsePaginationOptions) {
    const currentPage = ref(1);

    watch(currentPage, (newPage) => {
        router.get(route, { ...params, page: newPage }, { preserveState, preserveScroll });
    });

    function goToPage(page: number) {
        currentPage.value = page;
    }

    return {
        currentPage,
        goToPage,
    };
}
