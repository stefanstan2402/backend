<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";
import DataTable from "../components/DataTable.vue";

interface Category {
    id: number;
    name: string;
    description?: string;
}

const categories = ref<Category[]>([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const loading = ref(false);
const router = useRouter();

const fetchCategories = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await api.get(`/categories?page=${page}`);
        categories.value = data.data || [];
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } finally {
        loading.value = false;
    }
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
        <h2 class="text-2xl font-bold mb-4">Categories</h2>

        <button
            @click="router.push('/categories/new')"
            class="bg-green-500 px-3 py-1 rounded mb-4 btn btn-primary"
        >
            + Add Category
        </button>

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
    </div>
</template>
