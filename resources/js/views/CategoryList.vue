<script setup lang="ts">
import { useRouter } from "vue-router";
import {ref, onMounted} from 'vue';

import api from "../services/api";

const categories = ref([]);
const router = useRouter();

const fetchCategories = async () => {
    const {data} = await api.get("/categories");
    categories.value = data || [];
}

const deleteCategory = async (id: string) => {
    if (confirm("Delete this category?")) {
        await api.delete(`/categories/${id}`);
        await fetchCategories();
    }
}

const editCategory = async (id: string) => {
    await router.push({ path: `/categories/${id}/edit` });
}

onMounted(async () => {
    await fetchCategories();
});

</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Categories</h2>

        <button
            @click="router.push('/categories/new')"
            class="bg-green-500 px-3 py-1 rounded mb-4 btn btn-primary mx-1"
        >
            + Add Category
        </button>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="category in categories.data" :key="category.id">
                <td>{{ category.id }}</td>
                <td>{{ category.name }}</td>
                <td>{{ category.description }}</td>
                <td>
                    <button @click="editCategory(category.id)" class="btn btn-warning btn-sm me-1">Edit</button>
                    <button @click="deleteCategory(category.id)" class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped></style>
