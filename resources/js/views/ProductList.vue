<!--<template>-->
<!--    <div class="p-6">-->
<!--        <h2 class="text-2xl font-bold mb-4">Products</h2>-->

<!--        <button-->
<!--            @click="router.push('/products/new')"-->
<!--            class="bg-green-500  px-3 py-1 rounded mb-4 btn btn-primary mx-1"-->
<!--        >-->
<!--            + Add Product-->
<!--        </button>-->

<!--        <DataTable-->
<!--            :columns="[-->
<!--                { key: 'name', label: 'Name' },-->
<!--                { key: 'price', label: 'Price' },-->
<!--                { key: 'stock', label: 'Stock' },-->
<!--                { key: 'category.name', label: 'Category' }-->
<!--            ]"-->
<!--            :rows="products"-->
<!--            :pagination="pagination"-->
<!--            :loading="loading"-->
<!--            :onPageChange="fetchProducts"-->
<!--        >-->
<!--            <template #actions="{ row }">-->
<!--                <button-->
<!--                    @click="router.push(`/products/${row.id}/edit`)"-->
<!--                    class="bg-blue-500 px-2 py-1 rounded btn btn-warning mx-1"-->
<!--                >-->
<!--                    Edit-->
<!--                </button>-->
<!--                <button-->
<!--                    @click="deleteProduct(row.id)"-->
<!--                    class="bg-red-500 px-2 py-1 rounded btn btn-danger mx-1"-->
<!--                >-->
<!--                    Delete-->
<!--                </button>-->
<!--            </template>-->
<!--        </DataTable>-->

<!--    </div>-->
<!--</template>-->

<!--<script setup lang="ts">-->
<!--import { ref, onMounted } from "vue";-->
<!--import { useRouter } from "vue-router";-->
<!--import api from "../services/api";-->
<!--import DataTable from "../components/DataTable.vue";-->

<!--interface Category {-->
<!--    id: number;-->
<!--    name: string;-->
<!--    description?: string;-->
<!--}-->

<!--const products = ref([]);-->
<!--const pagination = ref({ current_page: 1, last_page: 1 });-->
<!--const router = useRouter();-->

<!--const fetchProducts = async (page = 1) => {-->
<!--    const { data } = await api.post(`/products/get?page=${page}`, {-->

<!--    });-->
<!--    products.value = data.data;-->
<!--    pagination.value = {-->
<!--        current_page: data.current_page,-->
<!--        last_page: data.last_page,-->
<!--    };-->
<!--};-->

<!--const deleteProduct = async (id) => {-->
<!--    if (confirm("Delete this product?")) {-->
<!--        await api.delete(`/products/${id}`);-->
<!--        fetchProducts(pagination.value.current_page);-->
<!--    }-->
<!--};-->

<!--onMounted(async() => {-->
<!--    await fetchProducts();-->
<!--});-->

<!--</script>-->


<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";
import DataTable from "../components/DataTable.vue";
import FilterSidebar from "../components/FilterSidebar.vue";

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
const filters = ref<Record<string, any>>({});

// Define filter fields dynamically
const filterFields = ref([
    { field: "name", type: "text", label: "Name" },
    { field: "price", type: "number", label: "Price" },
    { field: "stock", type: "number", label: "Stock" },
    { field: "category_id", type: "options", label: "Category", options: [] },
]);

// fetch categories to populate the select
const fetchCategoriesForFilter = async () => {
    const { data } = await api.get("/categories"); // adjust API endpoint if needed
    filterFields.value.find(f => f.field === "category_id")!.options = data.map((c: any) => ({
        id: c.id,
        name: c.name
    }));
};

onMounted(async () => {
    await fetchCategoriesForFilter();
    await fetchProducts();
});

const fetchProducts = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await api.post(`/products/get?page=${page}`, {
            ...filters.value
        });
        products.value = data.data;
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
    fetchProducts(1); // reset to first page
};

</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4 mx-5">Products</h2>

        <div class="mb-4 flex justify-between mx-5">
            <button @click="router.push('/products/new')" class="btn btn-success">+ Add Product</button>
            <button @click="filtersVisible = true" class="btn btn-secondary mx-1">🔍 Filters</button>
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
                <button @click="router.push(`/products/${row.id}/edit`)" class="btn btn-warning btn-sm mx-1">Edit</button>
                <button @click="deleteProduct(row.id)" class="btn btn-danger btn-sm mx-1">Delete</button>
            </template>
        </DataTable>

        <FilterSidebar
            :visible="filtersVisible"
            :filters="filterFields"
            :model-value="filters"
            @apply="applyFilters"
            @update:visible="v => (filtersVisible = v)"
        />
    </div>
</template>

