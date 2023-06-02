<script setup>
import { useChecklistStore } from "../stores/checklist";
import Swal from "sweetalert2";

const store = useChecklistStore();


async function create() {
    try {
        await store.create();
    } catch (e) {
        Swal.fire('Error', e.message, 'error');
    }
}
</script>

<template>
    <button @click="create"
            :class="{ 'pointer-events-none': store.hasPendingRequest }"
            class="bg-green-600 ring ring-green-400 hover:ring-green-500 text-white w-full h-[40px] rounded-lg mt-6 font-bold text-xl">
        <i class="fi fi-rs-disk"></i> Create
    </button>
</template>

<style scoped lang="scss"></style>
