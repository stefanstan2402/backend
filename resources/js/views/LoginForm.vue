<template>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-sm p-4" style="width: 400px;">
            <h2 class="text-center mb-4">Login</h2>

            <form @submit.prevent="login">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input v-model="form.email" type="email" class="form-control" />
                    <small class="text-danger">{{ errors.email }}</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input v-model="form.password" type="password" class="form-control" />
                    <small class="text-danger">{{ errors.password }}</small>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">Login</button>
                </div>

                <div class="text-center mt-3">
                    <router-link to="/register">Don’t have an account? Register</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive } from "vue";
import api from "../services/api";
import { useRouter } from "vue-router";
import { useNotification } from "@kyvg/vue3-notification";

const router = useRouter();
const { notify } = useNotification();

const form = reactive({
    email: "",
    password: ""
});

const errors = reactive({});

async function login() {
    try {
        errors.email = errors.password = "";

        const res = await api.post("/login", form);

        localStorage.setItem("api_token", res.data.access_token);
        localStorage.setItem("user", res.data.name);

        notify({ type: "success", title: "Success", text: "Logged in successfully!" });

        await router.push("/categories");
    } catch (err) {
        notify({
            type: "error",
            title: "Login failed",
            text: err.response?.data?.message || "Invalid credentials"
        });

        if (err.response && err.response.data && err.response.data.errors) {
            Object.assign(errors, err.response.data.errors);
        }
    }
}
</script>
