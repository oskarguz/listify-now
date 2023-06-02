<script setup>

import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";
import Toastify from "toastify-js";

const { getUrl } = storeToRefs(useChecklistStore());

async function copyLinkToClipboard() {
    try {
        await navigator.clipboard.writeText(getUrl.value);
        Toastify({
            text: 'Link has been copied to clipboard!'
        }).showToast();
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}
</script>

<template>
    <div>
        <h4 class="font-bold text-xl mb-2">Share</h4>
        <div class="flex rounded-md text-white ring ring-background-primary hover:ring-orange-500">
            <button @click="copyLinkToClipboard" class="bg-orange-400 justify-center rounded-l-md px-2 cursor-pointer font-bold">Copy link</button>
            <input @click="copyLinkToClipboard" class="flex-grow border-0 cursor-pointer text-background-secondary font-bold" type="text" :value="getUrl" :readonly="true">
            <button @click="copyLinkToClipboard" class="bg-orange-400 w-[40px] justify-center rounded-r-md px-2 cursor-pointer font-bold">
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
div.flex {
    //box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px;
}
</style>
