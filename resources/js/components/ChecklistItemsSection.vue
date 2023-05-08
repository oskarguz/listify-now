<script setup>

import ChecklistItem from "./ChecklistItem.vue";
import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";

const store = useChecklistStore();
const { items } = storeToRefs(store);
</script>

<template>
    <ChecklistItem v-for="item of items"
        v-bind="item"
        :key="item.id"
        class="mb-3"
    ></ChecklistItem>

    <ChecklistItem :item="null"></ChecklistItem>
</template>

<style scoped lang="scss">

</style>
