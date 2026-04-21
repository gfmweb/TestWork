<template>
    <div
        v-if="showBlock"
        class="mt-4"
    >
        <p
            v-if="selectionSummary !== null"
            class="mb-2 text-sm text-slate-600"
        >
            {{ selectionSummary }}
        </p>
        <nav
            v-if="totalPages > 1"
            class="flex flex-wrap items-center gap-2"
            aria-label="Pagination"
        >
        <button
            type="button"
            class="rounded-md border border-white/40 bg-white/30 px-3 py-1 text-sm text-slate-800 disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage <= 1"
            @click="$emit('change', currentPage - 1)"
        >
            Назад
        </button>

        <template
            v-for="item in pageItems"
            :key="item.key"
        >
            <span
                v-if="item.type === 'ellipsis'"
                class="px-1 text-sm text-slate-600"
            >
                ...
            </span>
            <button
                v-else
                type="button"
                class="rounded-md border px-3 py-1 text-sm"
                :class="item.page === currentPage
                    ? 'border-slate-700 bg-slate-700 text-white'
                    : 'border-white/40 bg-white/30 text-slate-800'"
                @click="$emit('change', item.page)"
            >
                {{ item.page }}
            </button>
        </template>

        <button
            type="button"
            class="rounded-md border border-white/40 bg-white/30 px-3 py-1 text-sm text-slate-800 disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage >= totalPages"
            @click="$emit('change', currentPage + 1)"
        >
            Вперед
        </button>
        </nav>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    currentPage: number;
    totalPages: number;
    /** Общее число записей в текущей выборке (с учётом фильтров) */
    total?: number;
    from?: number | null;
    to?: number | null;
}>();

const showBlock = computed(
    () => props.totalPages > 1 || (props.total !== undefined && props.total >= 0),
);

const selectionSummary = computed((): string | null => {
    if (props.total === undefined) {
        return null;
    }

    if (props.total === 0) {
        return 'В выборке доступно записей: 0';
    }

    if (props.from != null && props.to != null) {
        return `Показано ${props.from}–${props.to} из ${props.total}`;
    }

    return `В выборке доступно записей: ${props.total}`;
});

defineEmits<{
    change: [page: number];
}>();

type PageItem =
    | { key: string; type: 'page'; page: number }
    | { key: string; type: 'ellipsis' };

const pageItems = computed<PageItem[]>(() => {
    const maxVisiblePages = 5;

    if (props.totalPages <= maxVisiblePages) {
        return Array.from({ length: props.totalPages }, (_, index) => ({
            key: `page-${index + 1}`,
            type: 'page',
            page: index + 1,
        }));
    }

    let start = Math.max(1, props.currentPage - 2);
    let end = Math.min(props.totalPages, start + maxVisiblePages - 1);

    if (end - start + 1 < maxVisiblePages) {
        start = Math.max(1, end - maxVisiblePages + 1);
    }

    const items: PageItem[] = [];

    if (start > 1) {
        items.push({ key: 'page-1', type: 'page', page: 1 });
    }

    if (start > 2) {
        items.push({ key: 'ellipsis-start', type: 'ellipsis' });
    }

    for (let page = start; page <= end; page += 1) {
        items.push({ key: `page-${page}`, type: 'page', page });
    }

    if (end < props.totalPages - 1) {
        items.push({ key: 'ellipsis-end', type: 'ellipsis' });
    }

    if (end < props.totalPages) {
        items.push({ key: `page-${props.totalPages}`, type: 'page', page: props.totalPages });
    }

    return items;
});
</script>
