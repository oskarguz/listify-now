<script setup>

import { ref } from "vue";

const title = ref('New list');
const isEditing = ref(false);

let oldTitle = '';
function toggleEditAction() {
    isEditing.value = !isEditing.value;

    if (isEditing.value) {
        oldTitle = title.value;
    }
}

function saveTitle() {
    toggleEditAction();

    if (oldTitle === title.value) {
        return;
    }

    // @TODO save title
}
</script>

<template>
    <article>
        <div v-if="!isEditing">
            <p class="text-xl font-bold relative">
                {{ title }}
                <button @click="toggleEditAction" class="text-xl edit-btn">
                    <i class="fi fi-br-edit"></i>
                </button>
            </p>
        </div>
        <div v-else class="relative">
            <input class="rounded-lg" type="text" v-model.trim="title">
            <button @click="saveTitle" class="text-xl edit-btn">
                <i class="fi fi-br-edit"></i>
            </button>
        </div>
    </article>
</template>

<style scoped lang="scss">
button.edit-btn {
    position: absolute;
    margin-left: 8px;
    bottom: -50%;
}
input {
    border: none;
    &:focus, &:target {
        border: none;
        --tw-ring-color: none;
   }
}
</style>
