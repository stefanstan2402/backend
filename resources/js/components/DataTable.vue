<script setup lang="ts">
import { defineProps, computed } from "vue";

interface Column {
    key: string;
    label: string;
}

const props = defineProps<{
    columns: Column[];
    rows: Record<string, any>[];
    loading?: boolean;
    pagination?: { current_page: number; last_page: number };
    onPageChange?: (page: number) => void;
}>();

const getNestedValue = (obj: any, key: string) => {
    return key.split('.').reduce((acc, k) => (acc ? acc[k] : undefined), obj);
};

// Computed pages array with ellipsis
const displayPages = computed(() => {
    if (!props.pagination) return [];
    const pages: (number | string)[] = [];
    const { current_page, last_page } = props.pagination;

    if (last_page <= 3) {
        for (let i = 1; i <= last_page; i++) pages.push(i);
    } else {
        pages.push(1); // first page

        if (current_page > 2) pages.push('...');

        const start = Math.max(2, current_page - 1);
        const end = Math.min(last_page - 1, current_page + 1);

        for (let i = start; i <= end; i++) pages.push(i);

        if (current_page < last_page - 1) pages.push('...');
        pages.push(last_page); // last page
    }

    return pages;
});

const goToPage = (page: number | string) => {
    if (typeof page === 'number' && props.onPageChange) props.onPageChange(page);
};

const prevPage = () => {
    if (props.pagination && props.pagination.current_page > 1) {
        goToPage(props.pagination.current_page - 1);
    }
};

const nextPage = () => {
    if (props.pagination && props.pagination.current_page < props.pagination.last_page) {
        goToPage(props.pagination.current_page + 1);
    }
};
</script>

<template>
    <div class="w-full mx-5">
        <div v-if="loading" class="text-center py-4">Loading...</div>

        <table v-else class="table table-hover w-full border-collapse border">
            <thead class="bg-gray-200">
            <tr>
                <th v-for="col in columns" :key="col.key" class="border px-2 py-1">
                    {{ col.label }}
                </th>
                <th class="border px-2 py-1">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="!rows.length">
                <td :colspan="columns.length + 1" class="text-center py-4 text-gray-500">
                    No data found.
                </td>
            </tr>
            <tr v-for="row in rows" :key="row.id">
                <td v-for="col in columns" :key="col.key" class="border px-2 py-1">
                    {{ getNestedValue(row, col.key) }}
                </td>
                <td class="border px-2 py-1">
                    <slot name="actions" :row="row" />
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="pagination" class="mt-4 flex gap-1 justify-start items-center">
            <button
                @click="prevPage"
                :disabled="pagination.current_page === 1"
                class="px-3 py-1 rounded border bg-gray-100 hover:bg-gray-200 disabled:opacity-50"
            >
                &laquo; Prev
            </button>

            <button
                v-for="page in displayPages"
                :key="page + '-' + pagination.current_page"
                @click="goToPage(page)"
                :disabled="page === '...' || page === pagination.current_page"
                :class="[
          'px-3 py-1 rounded border',
          page === pagination.current_page
            ? 'bg-black text-white cursor-not-allowed'
            : page === '...'
            ? 'cursor-default'
            : 'bg-gray-100 hover:bg-gray-200'
        ]"
            >
                {{ page }}
            </button>

            <button
                @click="nextPage"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1 rounded border bg-gray-100 hover:bg-gray-200 disabled:opacity-50"
            >
                Next &raquo;
            </button>
        </div>
    </div>
</template>
