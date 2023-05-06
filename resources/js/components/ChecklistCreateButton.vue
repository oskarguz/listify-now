<script setup>
    import { useChecklistStore } from "../stores/checklist";

    const store = useChecklistStore();
</script>

<template>
    <button @click="store.create"
            :class="{ 'pointer-events-none': store.hasPendingRequest }"
            class="bg-green-400 w-full h-[40px] rounded-lg mt-6 font-bold text-xl">
        <i class="fi fi-rs-disk"></i> Create
    </button>
</template>

<style scoped lang="scss"></style>
