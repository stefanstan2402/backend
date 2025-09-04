<template>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-sm p-4 text-center" style="width: 400px;">
            <h2 class="text-danger mb-3">🚫 Access Denied</h2>
            <p class="mb-4">
                You are not authorized to view this page.<br />
                Please <router-link to="/login">log in</router-link>.
            </p>
            <router-link to="/login" class="btn btn-primary">Go to Login</router-link>
        </div>
    </div>
</template>

<style scoped>
.card {
    border-radius: 12px;
}
</style>
