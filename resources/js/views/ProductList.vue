<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";

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

onMounted(() => fetchProducts());
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

        <table class="w-full border-collapse border table table-hover">
            <thead>
            <tr class="bg-gray-200">
                <th class="border px-2 py-1">Name</th>
                <th class="border px-2 py-1">Price</th>
                <th class="border px-2 py-1">Stock</th>
                <th class="border px-2 py-1">Category</th>
                <th class="border px-2 py-1">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="p in products" :key="p.id">
                <td class="border px-2 py-1">{{ p.name }}</td>
                <td class="border px-2 py-1">{{ p.price }}</td>
                <td class="border px-2 py-1">{{ p.stock }}</td>
                <td class="border px-2 py-1">{{ p.category?.name }}</td>
                <td class="border px-2 py-1 flex gap-2">
                    <button
                        @click="router.push(`/products/${p.id}/edit`)"
                        class="bg-blue-500 px-2 mx-1 py-1 rounded btn btn-warning"
                    >
                        Edit
                    </button>
                    <button
                        @click="deleteProduct(p.id)"
                        class="bg-red-500  px-2 py-1 rounded btn btn-danger mx-1"
                    >
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex gap-2">
            <button
                v-for="page in pagination.last_page"
                :key="page"
                @click="fetchProducts(page)"
                :class="['px-3 py-1 rounded', page === pagination.current_page ? 'text-black' : 'text-white']"
            >
                {{ page }}
            </button>
        </div>
    </div>
</template>
