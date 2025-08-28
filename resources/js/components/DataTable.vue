    <script setup lang="ts">
    import { defineProps } from "vue";

    interface Column {
        key: string;      // field name, can be nested like 'category.name'
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

    </script>

    <template>
        <div class="w-full">
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

            <div v-if="pagination" class="mt-4 flex gap-2 text-right">
                <button
                    v-for="page in pagination.last_page"
                    :key="page"
                    @click="props.onPageChange?.(page)"
                    :disabled="page === pagination.current_page"
                    :class="[
              'px-3 py-1 rounded',
              page === pagination.current_page
                ? 'text-white bg-black cursor-not-allowed'
                : 'text-black bg-gray-100 hover:bg-gray-200'
            ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>
    </template>
