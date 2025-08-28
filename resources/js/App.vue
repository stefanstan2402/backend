<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
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
                </div>
            </div>
        </nav>

        <!-- 👇 All route content renders here -->
        <main class="p-6">
            <router-view />
        </main>

        <notifications position="top right"/>
    </div>
</template>

<script setup lang="ts">
import { Notifications } from '@kyvg/vue3-notification'
</script>
