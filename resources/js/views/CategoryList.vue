<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";
import DataTable from "../components/DataTable.vue";
import FilterSidebar from "../components/FilterSidebar.vue"; // ⬅️ add
import { filtersConfig } from "../config/filtersConfig";            // ⬅️ add

interface Category {
    id: number;
    name: string;
    description?: string;
}

const categories = ref<Category[]>([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const loading = ref(false);
const router = useRouter();

const filtersVisible = ref(false);
const filters = ref<Record<string, any>>({});

const filterFields = filtersConfig.categories ?? [
    { field: "name", type: "text", label: "Name" },
    { field: "description", type: "text", label: "Description" },
];

const fetchCategories = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await api.post(`/categories/get?page=${page}`, {
            ...filters.value,
        });
        categories.value = data.data || [];
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } finally {
        loading.value = false;
    }
};

const applyFilters = (newFilters: Record<string, any>) => {
    filters.value = newFilters;
    fetchCategories(1); // reset to first page on new filters
};

const deleteCategory = async (id: number) => {
    if (confirm("Delete this category?")) {
        await api.delete(`/categories/${id}`);
        await fetchCategories(pagination.value.current_page);
    }
};

onMounted(() => fetchCategories());
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4 mx-5">Categories</h2>

        <div class="flex justify-between items-center mb-4 mx-5">
            <button
                @click="router.push('/categories/new')"
                class="bg-green-500 px-3 py-1 rounded btn btn-success"
            >
                + Add Category
            </button>

            <button
                @click="filtersVisible = true"
                class="bg-gray-600 px-3 py-1 btn btn-secondary mx-1"
            >
                🔍 Filters
            </button>
        </div>

        <DataTable
            :columns="[
        { key: 'id', label: 'ID' },
        { key: 'name', label: 'Name' },
        { key: 'description', label: 'Description' }
      ]"
            :rows="categories"
            :pagination="pagination"
            :loading="loading"
            :onPageChange="fetchCategories"
        >
            <template #actions="{ row }">
                <button
                    @click="router.push(`/categories/${row.id}/edit`)"
                    class="bg-blue-500 px-2 py-1 rounded btn btn-warning mx-1"
                >
                    Edit
                </button>
                <button
                    @click="deleteCategory(row.id)"
                    class="bg-red-500 px-2 py-1 rounded btn btn-danger mx-1"
                >
                    Delete
                </button>
            </template>
        </DataTable>

        <!-- ⬇️ new: reusable filter sidebar -->
        <FilterSidebar
            :visible="filtersVisible"
            :filters="filterFields"
            :model-value="filters"
            @apply="applyFilters"
            @update:visible="v => (filtersVisible = v)"
        />
    </div>
</template>
