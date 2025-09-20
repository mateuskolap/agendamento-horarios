<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

type ModalSize = 'sm' | 'md' | 'lg' | 'xl';

const props = defineProps<{
    id: string;
    title: string;
    size: ModalSize;
    isOpen: boolean;
    innerClass?: string;
}>();

const emit = defineEmits(['update:is-open']);

function closeModal() {
    emit('update:is-open', false);
}

const sizeClasses: Record<ModalSize, string> = {
    sm: 'max-w-sm',
    md: 'max-w-lg',
    lg: 'max-w-2xl',
    xl: 'max-w-5xl',
};
const modalHeight = ref('max-h-[calc(100%-5rem)]');
const contentHeight = ref('max-h-[calc(100%-4rem)]');

watch(
    () => props.isOpen,
    (isOpen) => {
        if (isOpen) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    },
);

onMounted(() => {
    const handleKey = (event: KeyboardEvent) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    };
    window.addEventListener('keydown', handleKey);

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', handleKey);
    });
});
</script>

<template>
    <div
        v-show="isOpen"
        :id="props.id"
        class="fixed top-0 right-0 left-0 z-50 h-[calc(100%-1rem)] max-h-full w-full overflow-x-hidden overflow-y-auto p-4 md:inset-0"
        tabindex="-1"
        :aria-hidden="isOpen ? 'false' : 'true'"
    >
        <div class="fixed inset-0 z-40 backdrop-blur-sm" @click="closeModal" />
        <Transition name="modalanim">
            <div
                v-show="isOpen"
                :class="[sizeClasses[props.size], innerClass, modalHeight]"
                class="items-top relative z-50 mx-auto flex w-full justify-center"
            >
                <div class="bg-primary-light dark:bg-primary-dark relative min-w-full rounded-lg bg-sidebar py-4 shadow">
                    <div class="mb-4 flex items-center justify-between rounded-t border-b px-4 pb-4 sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-300">
                            {{ props.title }}
                        </h3>
                        <button
                            type="button"
                            class="ml-auto inline-flex cursor-pointer items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-300 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="closeModal"
                        >
                            <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <div :class="contentHeight" class="overflow-y-auto px-4">
                        <slot />
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.modalanim-enter-active,
.modalanim-leave-active {
    transition: all 0.3s cubic-bezier(0, 1, 1, 1);
}

.modalanim-enter-from {
    transform: translateY(100px);
    opacity: 0;
}

.modalanim-enter-to {
    transform: translateY(0);
    opacity: 1;
}
</style>
