<script setup>

const url = window.location.href;

async function copyLinkToClipboard() {
    try {
        await navigator.clipboard.writeText(url);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}
</script>

<template>
    <div>
        <h4 class="font-bold text-xl">Share</h4>
        <div class="flex">
            <button @click="copyLinkToClipboard" class="bg-orange-400 justify-center rounded-l-md px-2">Copy link</button>
            <input @click="copyLinkToClipboard" class="flex-grow border-0" type="text" :value="url" :readonly="true">
            <button @click="copyLinkToClipboard" class="bg-orange-400 w-[40px] justify-center rounded-r-md px-2">
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
