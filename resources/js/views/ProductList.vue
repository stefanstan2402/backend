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
}
interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    category: Category;
}

const products = ref<Product[]>([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const router = useRouter();
const loading = ref(false);

const filtersVisible = ref(false);
const filters = ref<Record<string, any>>({
    name: "",
    price: null,
    stock: null,
    category_id: null,
});

const categories = ref<Category[]>([]);

const fetchCategoriesForFilter = async () => {
    const { data } = await api.get("/categories");
    categories.value = data;
};

const fetchProducts = async (page = 1) => {
    loading.value = true;
    try {
        const params: Record<string, any> = {
            page,
            ...buildQueryParams(filters.value, filtersConfig.products)
        };

        const { data } = await api.get("/products/get", { params });
        products.value = data.data;
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
        };
    } finally {
        loading.value = false;
    }
};

const applyFilters = () => {
    fetchProducts(1);
    filtersVisible.value = false;
}

const resetFilters = () => {
    filters.value = { name: "", price: null, stock: null, category_id: "" };
    fetchProducts(1);
};

const deleteProduct = async (id: number) => {
    if (confirm("Delete this product?")) {
        try{
            await api.delete(`/products/${id}`);
        } catch (error) {
            console.log(error);
        }
        await fetchProducts(pagination.value.current_page);
    }
};

onMounted(async () => {
    await fetchCategoriesForFilter();
    await fetchProducts();
});
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4 mx-5">Products</h2>

        <div class="mb-4 flex justify-between mx-5">
            <button @click="router.push('/products/new')" class="btn btn-success">
                + Add Product
            </button>
            <button @click="filtersVisible = true" class="btn btn-secondary mx-1">
                🔍 Filters
            </button>
        </div>

        <DataTable
            :columns="[
        { key: 'name', label: 'Name' },
        { key: 'price', label: 'Price' },
        { key: 'stock', label: 'Stock' },
        { key: 'category.name', label: 'Category' }
      ]"
            :rows="products"
            :pagination="pagination"
            :loading="loading"
            :onPageChange="fetchProducts"
        >
            <template #actions="{ row }">
                <button
                    @click="router.push(`/products/${row.id}/edit`)"
                    class="btn btn-warning btn-sm mx-1"
                >
                    Edit
                </button>
                <button
                    @click="deleteProduct(row.id)"
                    class="btn btn-danger btn-sm mx-1"
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

            <!-- Slot content for filters -->
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
                <label for="filterPrice" class="form-label fw-semibold">Price</label>
                <input
                    v-model.number="filters.price"
                    type="number"
                    id="filterPrice"
                    class="form-control shadow-sm"
                    placeholder="Enter price"
                />
            </div>

            <div class="mb-3">
                <label for="filterStock" class="form-label fw-semibold">Stock</label>
                <input
                    v-model.number="filters.stock"
                    type="number"
                    id="filterStock"
                    class="form-control shadow-sm"
                    placeholder="Enter stock quantity"
                />
            </div>

            <div class="mb-3">
                <label for="filterCategory" class="form-label fw-semibold">Category</label>
                <select
                    v-model="filters.category_id"
                    id="filterCategory"
                    class="form-select shadow-sm"
                >
                    <option value="">All</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                        {{ cat.name }}
                    </option>
                </select>
            </div>

            <template #actions>
                <button class="btn btn-secondary w-100 mt-2" @click="resetFilters">Reset</button>
                <button class="btn btn-primary w-100 mt-2" @click="applyFilters">Apply</button>
            </template>
        </FilterSidebar>
    </div>
</template>
