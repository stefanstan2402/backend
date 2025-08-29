<template>
    <div class="container mt-5">
        <h2 class="mb-4">
            {{ isEdit ? "Edit Product" : "Add Product" }}
        </h2>

        <form @submit.prevent="saveProduct">
            <!-- Name -->
            <div class="mb-3">
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Name"
                    class="form-control"
                />
            </div>

            <!-- Price -->
            <div class="mb-3">
                <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    placeholder="Price"
                    class="form-control"
                />
            </div>

            <!-- Stock -->
            <div class="mb-3">
                <input
                    v-model="form.stock"
                    type="number"
                    placeholder="Stock"
                    class="form-control"
                />
            </div>

            <!-- Category -->
            <div class="mb-3">
                <select v-model="form.category_id" class="form-select">
                    <option value="">Select Category</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">
                        {{ c.name }}
                    </option>
                </select>
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../services/api";

const route = useRoute();
const router = useRouter();
const isEdit = ref(false);

const form = ref({ name: "", price: "", stock: "", category_id: "" });
const categories = ref([]);

const fetchCategories = async () => {
    try {
        const { data } = await api.get("/categories");
        categories.value = data.data;
    } catch (err) {
        console.error(err);
    }
};

onMounted(async () => {
    await fetchCategories();

    if (route.params.id) {
        isEdit.value = true;
        const { data } = await api.get(`/products/${route.params.id}`);
        form.value = {
            name: data.name,
            price: data.price,
            stock: data.stock,
            category_id: data.category_id,
        };
    }
});

const saveProduct = async () => {
    if (isEdit.value) {
        await api.put(`/products/${route.params.id}`, form.value);
    } else {
        await api.post("/products", form.value);
    }
    await router.push("/products");
};
</script>
