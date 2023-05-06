<script setup>

import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";

const { getUrl } = storeToRefs(useChecklistStore());

async function copyLinkToClipboard() {
    try {
        await navigator.clipboard.writeText(getUrl.value);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}
</script>

<template>
    <div>
        <h4 class="font-bold text-xl">Share</h4>
        <div class="flex">
            <button @click="copyLinkToClipboard" class="bg-orange-400 justify-center rounded-l-md px-2 cursor-pointer">Copy link</button>
            <input @click="copyLinkToClipboard" class="flex-grow border-0 cursor-pointer" type="text" :value="getUrl" :readonly="true">
            <button @click="copyLinkToClipboard" class="bg-orange-400 w-[40px] justify-center rounded-r-md px-2 cursor-pointer">
                <i class="fi-bs-share"></i>
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
input {
    --tw-ring-color: none;
    &:focus {
        --tw-ring-color: none;
    }
}
</style>
