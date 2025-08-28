<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../services/api";
import { useNotification } from "@kyvg/vue3-notification";

const route = useRoute();
const router = useRouter();
const name = ref("");
const description = ref("");
const isEdit = ref(false);
const { notify }  = useNotification()


onMounted(async () => {
    if (route.params.id) {
        isEdit.value = true;
        const { data } = await api.get(`/categories/${route.params.id}`);
        name.value = data.name;
        description.value = data.description;
    }
});

const saveCategory = async () => {
    if (isEdit.value) {
        try{
            await api.put(`/categories/${route.params.id}`, { name: name.value, description: description.value });
            notify({
                type: "success",
                title: "Categorie modificata cu succes!",
                text: "Success!",
            });
        } catch (error) {
            notify({
                type: "error",
                title: error.message,
                text: "Eroare!",
            });
        }
    } else {
        try{
            await api.post("/categories", { name: name.value, description: description.value }, {
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });notify({
                type: "success",
                title: "Categorie salvata cu succes!",
                text: "Success!",
            });
        } catch (error) {
            notify({
                type: "error",
                title: error.message,
                text: "Eroare!",
            });
        }

    }
    await router.push("/categories");
};
</script>

<template>
    <div class="form-container">
        <div class="form-card">
            <h2>
                {{ isEdit ? " Edit Category" : " Add Category" }}
            </h2>

            <form @submit.prevent="saveCategory">
                <div class="form-group">
                    <label>Category Name</label>
                    <input
                        v-model="name"
                        placeholder="Enter category name"
                        class="form-input"
                    />
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea
                        v-model="description"
                        placeholder="Enter category description"
                        class="form-input"
                        rows="3"
                    ></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    {{ isEdit ? "Update" : "Save" }}
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.form-container {
    display: flex;
    justify-content: center;
    padding: 2rem;
}

.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    width: 100%;
    max-width: 500px;
}

h2 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #444;
}

.form-input {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-input:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}

.btn-submit {
    background: #10b981;
    color: #fff;
    font-weight: bold;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    transition: background 0.2s;
}

.btn-submit:hover {
    background: #059669;
}
</style>
