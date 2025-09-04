<template>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-sm p-4" style="width: 400px;">
            <h2 class="text-center mb-4">Register</h2>

            <form @submit.prevent="register">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input v-model="form.name" type="text" class="form-control" />
                    <small class="text-danger">{{ errors.name }}</small>
                </div>

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

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password" class="form-control" />
                </div>

                <!-- Centered button -->
                <div class="d-grid">
                    <button class="btn btn-primary">Register</button>
                </div>

                <!-- Link -->
                <div class="text-center mt-3">
                    <router-link to="/login">Already have an account? Login</router-link>
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
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
});

const errors = reactive({});

async function register() {
    try {
        errors.name = errors.email = errors.password = "";
        const res = await api.post("/register", form);
        notify({ type: "success", title: "Success", text: "Registered successfully! Now login!" });
        await router.push("/login");
    } catch (err) {
        notify({
            type: "error",
            title: "Registration Failed",
            text: err.response?.data?.message || "Please check your input."
        });

        if (err.response && err.response.data && err.response.data.errors) {
            Object.assign(errors, err.response.data.errors);
        }
    }
}
</script>
