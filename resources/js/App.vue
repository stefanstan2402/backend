<template>
    <div class="min-h-screen bg-gray-100">
        <nav
            v-if="isLoggedIn"
            class="navbar navbar-expand-lg navbar-dark bg-dark mb-4"
        >
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Categories & Products app</a>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/categories">Categories</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/products">Products</router-link>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item text-white mx-5">
                            Hello, {{userName}}!
                        </li>
                        <li class="nav-item">
                            <button @click="logout" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-6">
            <router-view />
        </main>

        <notifications position="top right" />
    </div>
</template>

<script setup lang="ts">
import { Notifications } from "@kyvg/vue3-notification";
import { ref, watch } from "vue";
import api from "./services/api";
import { useRouter } from "vue-router";

const router = useRouter();
const isLoggedIn = ref(false);
const userName = ref('test');

function checkAuth() {
    isLoggedIn.value = !!localStorage.getItem("api_token");
    userName.value = localStorage.getItem("user");
}

checkAuth();

watch(
    () => router.currentRoute.value,
    () => checkAuth()
);

async function logout() {
    try {
        await api.post("/logout");
    } catch (err) {
        console.error(err);
    } finally {
        localStorage.removeItem("api_token");
        checkAuth();
        await router.push("/login");
    }
}
</script>
