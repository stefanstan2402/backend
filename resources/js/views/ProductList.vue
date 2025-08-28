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

const products = ref([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const router = useRouter();

const fetchProducts = async (page = 1) => {
    const { data } = await api.get(`/products?page=${page}`);
    products.value = data.data;
    pagination.value = {
        current_page: data.current_page,
        last_page: data.last_page,
    };
};

const deleteProduct = async (id) => {
    if (confirm("Delete this product?")) {
        await api.delete(`/products/${id}`);
        fetchProducts(pagination.value.current_page);
    }
};

onMounted(async() => {
    await fetchProducts();
});

</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Products</h2>

        <button
            @click="router.push('/products/new')"
            class="bg-green-500  px-3 py-1 rounded mb-4 btn btn-primary mx-1"
        >
            + Add Product
        </button>

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
                    class="bg-blue-500 px-2 py-1 rounded btn btn-warning mx-1"
                >
                    Edit
                </button>
                <button
                    @click="deleteProduct(row.id)"
                    class="bg-red-500 px-2 py-1 rounded btn btn-danger mx-1"
                >
                    Delete
                </button>
            </template>
        </DataTable>

    </div>
</template>
