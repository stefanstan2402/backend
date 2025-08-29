<script setup>
import { ref, watch } from "vue";
import { Button } from "primevue/button";
import { Sidebar } from "primevue/sidebar";
import { InputText } from "primevue/inputtext";
import { InputNumber } from "primevue/inputnumber";
import { Dropdown } from "primevue/dropdown";

const props = defineProps({
    visible: Boolean,
    filters: Array, // config (from filtersConfig.js)
    modelValue: Object // current filter values
});
const emit = defineEmits(["update:visible", "apply"]);

const localFilters = ref({ ...props.modelValue });

watch(
    () => props.modelValue,
    (val) => (localFilters.value = { ...val })
);

const applyFilters = () => {
    emit("apply", localFilters.value);
    emit("update:visible", false);
};
</script>

<template>
    <Sidebar v-model:visible="props.visible" position="left" class="w-80">
        <h2 class="text-xl font-bold mb-4">Filters</h2>

        <div v-for="filter in props.filters" :key="filter.field" class="mb-4">
            <label class="block font-medium mb-1">{{ filter.label }}</label>

            <!-- Text filter -->
            <InputText v-if="filter.type === 'text'" v-model="localFilters[filter.field]" class="w-full" />

            <!-- Number filter -->
            <InputNumber
                v-if="filter.type === 'number'"
                v-model="localFilters[filter.field]"
                class="w-full"
            />

            <!-- Options filter -->
            <Dropdown
                v-if="filter.type === 'options'"
                v-model="localFilters[filter.field]"
                :options="filter.options"
                optionLabel="name"
                optionValue="id"
                placeholder="Select"
                class="w-full"
            />
        </div>

        <Button label="Apply Filters" class="w-full mt-6" @click="applyFilters" />
    </Sidebar>
</template>
