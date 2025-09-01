<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";
import DataTable from "../components/DataTable.vue";
import FilterSidebar from "../components/FilterSidebar.vue";
import { filtersConfig } from "../config/filtersConfig";
import { buildQueryParams } from "../utils/queryBuilder";

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
const filters = ref<Record<string, any>>({
    name: "",
    description: "",
});

const fetchCategories = async (page = 1) => {
    loading.value = true;
    try {
        const params: Record<string, any> = {
            page,
            ...buildQueryParams(filters.value, filtersConfig.categories)
        };

        const { data } = await api.get("/categories/get", { params });

        categories.value = data.data || [];
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } finally {
        loading.value = false;
    }
};

const applyFilters = () => {
    fetchCategories(1);
    filtersVisible.value = false;
};

const resetFilters = () => {
    filters.value = { name: "", description: "" };
    fetchCategories(1);
};

const deleteCategory = async (id: number) => {
    if (confirm("Delete this category?")) {
        try {
            await api.delete(`/categories/${id}`);
        } catch (error) {
            console.log(error);
        }

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
                class="bg-green-500 px-3 rounded btn btn-success"
            >
                + Add Category
            </button>

            <button
                @click="filtersVisible = true"
                class="bg-gray-600 px-3 btn btn-secondary mx-1"
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
                    class="bg-blue-500 px-2 py-1 rounded btn btn-warning mx-1"
                    @click="router.push(`/categories/${row.id}/edit`)"
                >
                    Edit
                </button>
                <button
                    class="bg-red-500 px-2 py-1 rounded btn btn-danger mx-1"
                    @click="deleteCategory(row.id)"
                >
                    Delete
                </button>
            </template>
        </DataTable>

        <!-- Filter Sidebar -->
        <FilterSidebar
            v-model:visible="filtersVisible"
            :modelValue="filters"
            @apply="applyFilters"
        >
            <div class="mb-3">
                <label for="filterName" class="form-label fw-semibold">Name</label>
                <input
                    v-model="filters.name"
                    type="text"
                    id="filterName"
                    class="form-control shadow-sm"
                    placeholder="Enter name"
                />
            </div>

            <div class="mb-3">
                <label for="filterDescription" class="form-label fw-semibold">Description</label>
                <input
                    v-model="filters.description"
                    type="text"
                    id="filterDescription"
                    class="form-control shadow-sm"
                    placeholder="Enter description"
                />
            </div>

            <!-- Slot for action buttons -->

            <template #actions>
                <button
                    class="btn btn-secondary w-100 mt-2"
                    @click="resetFilters"
                >
                    Reset
                </button>
                <button
                    class="btn btn-primary w-100 mt-2"
                    @click="applyFilters"
                >
                    Apply
                </button>
            </template>
        </FilterSidebar>
    </div>
</template>
