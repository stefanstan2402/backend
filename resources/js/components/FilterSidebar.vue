<template>
    <div
        v-if="visible"
        class="filter-overlay"
        @click="closeSidebar"
    ></div>

    <div
        class="filter-sidebar"
        :class="{ 'filter-sidebar--visible': visible }"
    >
        <h4 class="mb-4">Filters</h4>

        <slot>
            <p>No filters defined.</p>
        </slot>

        <div class="mt-4 flex flex-col gap-2">
            <slot name="actions">
                <button class="btn btn-primary w-100" @click="applyDefault">
                    Apply
                </button>
                <button class="btn btn-secondary w-100" @click="closeSidebar">
                    Cancel
                </button>
            </slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, watch } from "vue";

const props = defineProps({
    visible: { type: Boolean, default: false },
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

const applyDefault = () => {
    emit("apply", { ...localFilters });
    emit("update:visible", false);
};

const closeSidebar = () => {
    emit("update:visible", false);
};
</script>

<style scoped>
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

.filter-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 350px;
    height: 100vh;
    background: #f8f9fa;
    border-left: 1px solid #dee2e6;
    padding: 1.5rem;
    z-index: 1050;
    transition: right 0.3s ease-in-out;
    overflow-y: auto;
}

.filter-sidebar--visible {
    right: 0;
}
</style>
