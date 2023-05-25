<script setup>

import { ref } from "vue";
import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";

const checklistStore = useChecklistStore();

const { name } = storeToRefs(checklistStore);
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

    checklistStore.updateName(name.value).then((r) => {
        console.log(r.message);
    }).catch((e) => {
        console.error(e.message);
        name.value = oldName;
        toggleEditAction();
    });
}

let pendingRequest = false;
async function deleteChecklist() {
    if (pendingRequest) {
        return;
    }

    let c = confirm('Are you sure?'); // @TODO replace with some nice alerts :)
    if (!c) {
        return;
    }

    pendingRequest = true;
    try {
        await checklistStore.deleteChecklist();

        alert('Checklist has been deleted!'); // @TODO replace with some nice alerts :)
        window.location.replace('/dashboard');
    } catch (error) {
        console.error(error);
    }
    pendingRequest = false;
}
</script>

<template>
    <article class="flex h-[50px]">
        <div v-if="!isEditing" class="mt-auto">
            <p class="text-xl font-bold relative">
                {{ name }}
                <button @click="toggleEditAction" class="text-xl edit-btn">
                    <i class="fi fi-br-edit"></i>
                </button>
            </p>
        </div>
        <div v-else class="relative mt-auto">
            <input class="rounded-lg" type="text" v-model.trim="name">
            <button @click="saveName" class="text-xl edit-btn">
                <i class="fi fi-br-edit"></i>
            </button>
        </div>
        <div v-if="checklistStore.isCreatedByMe" class="ml-auto mt-auto flex">
            <div class="border border-l-2 mr-4 border-black"></div>
            <button @click="deleteChecklist" class="bg-red-500 px-2 h-[40px] rounded font-bold text-sm">
                DELETE <i class="fi-br-trash align-middle"></i>
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
