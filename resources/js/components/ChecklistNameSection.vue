<script setup>

import { ref } from "vue";
import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";

const store = useChecklistStore();

const { name } = storeToRefs(store);
const isEditing = ref(false);

let oldName = '';
function toggleEditAction() {
    isEditing.value = !isEditing.value;

    if (isEditing.value) {
        oldName = name.value;
    }
}

function saveName() {
    toggleEditAction();

    if (oldName === name.value) {
        return;
    }

    store.updateName(name.value).then((r) => {
        console.log(r.message);
    }).catch((e) => {
        console.error(e.message);
        name.value = oldName;
        toggleEditAction();
    });
}
</script>

<template>
    <article>
        <div v-if="!isEditing">
            <p class="text-xl font-bold relative">
                {{ name }}
                <button @click="toggleEditAction" class="text-xl edit-btn">
                    <i class="fi fi-br-edit"></i>
                </button>
            </p>
        </div>
        <div v-else class="relative">
            <input class="rounded-lg" type="text" v-model.trim="name">
            <button @click="saveName" class="text-xl edit-btn">
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
