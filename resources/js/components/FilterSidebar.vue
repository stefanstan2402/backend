<template>
    <!-- Overlay -->
    <div
        v-if="visible"
        class="filter-overlay"
        @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <div
        class="filter-sidebar"
        :class="{ 'filter-sidebar--visible': visible }"
    >
        <h4 class="mb-4">Filters</h4>

        <div v-for="filter in filters" :key="filter.field" class="mb-3">
            <label class="form-label">{{ filter.label }}</label>

            <!-- Text filter -->
            <input
                v-if="filter.type === 'text'"
                type="text"
                class="form-control"
                v-model="localFilters[filter.field]"
            />

            <!-- Number filter -->
            <input
                v-if="filter.type === 'number'"
                type="number"
                class="form-control"
                v-model.number="localFilters[filter.field]"
            />

            <!-- Options filter -->
            <select
                v-if="filter.type === 'options'"
                class="form-select"
                v-model="localFilters[filter.field]"
            >
                <option value="">Select {{ filter.label }}</option>
                <option
                    v-for="option in filter.options"
                    :key="option.id"
                    :value="option.id"
                >
                    {{ option.name }}
                </option>
            </select>
        </div>

        <button class="btn btn-primary w-100 mt-3" @click="applyFilters">
            Apply
        </button>
        <button class="btn btn-secondary w-100 mt-2" @click="closeSidebar">
            Cancel
        </button>
    </div>
</template>

<script setup lang="ts">
import { reactive, watch } from "vue";

const props = defineProps({
    visible: { type: Boolean, default: false },
    filters: { type: Array, default: () => [] },
    modelValue: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:visible", "apply"]);

const localFilters = reactive({ ...props.modelValue });

watch(
    () => props.modelValue,
    (val) => {
        Object.assign(localFilters, val);
    }
);

const applyFilters = () => {
    emit("apply", { ...localFilters });
    emit("update:visible", false);
};

const closeSidebar = () => {
    emit("update:visible", false);
};
</script>

<style scoped>
/* Overlay with blur */
.filter-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
    z-index: 1040;
}

/* Sidebar container */
.filter-sidebar {
    position: fixed;
    top: 0;
    right: -400px; /* hide initially */
    width: 350px;
    height: 100vh;
    background: #f8f9fa;
    border-left: 1px solid #dee2e6;
    padding: 1.5rem;
    z-index: 1050;
    transition: right 0.3s ease-in-out;
    overflow-y: auto;
}

/* Slide in when visible */
.filter-sidebar--visible {
    right: 0;
}
</style>
