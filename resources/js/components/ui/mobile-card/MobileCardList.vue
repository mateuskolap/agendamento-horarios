<script lang="ts" setup>
import MobileCard from './MobileCard.vue';

type KeyGetter<T> = string | ((item: T) => string | number);

interface Props<T = any> {
    items: T[];
    itemKey?: KeyGetter<T>;
}

const props = defineProps<Props>();

function getKey(item: any): string | number {
    if (typeof props.itemKey === 'function') {
        return (props.itemKey as (i: any) => string | number)(item);
    }
    const key = (props.itemKey as string) ?? 'id';
    return item?.[key] ?? Math.random().toString(36).slice(2);
}
</script>

<template>
    <div class="space-y-3" v-bind="$attrs">
        <MobileCard v-for="item in items" :key="getKey(item)">
            <template #header-left>
                <slot :item="item" name="header-left" />
            </template>

            <template #header-right>
                <slot :item="item" name="header-right" />
            </template>

            <slot :item="item" />
        </MobileCard>
    </div>
</template>
