<script setup>

import { nextTick, ref } from "vue";
import { useChecklistStore } from "../stores/checklist";
import { storeToRefs } from "pinia";
import Swal from "sweetalert2";
import Toastify from "toastify-js";
import ChecklistChangeVisibilityButton from "./ChecklistChangeVisibilityButton.vue";

const checklistStore = useChecklistStore();

const { name } = storeToRefs(checklistStore);
const isEditing = ref(false);
const nameInput = ref(null);

let oldName = '';
function toggleEditAction() {
    isEditing.value = !isEditing.value;

    if (isEditing.value) {
        oldName = name.value;
        nextTick(() => {
            if (nameInput.value) {
                nameInput.value.focus();
            }
        })
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
        Toastify({
            text: e.message,
        }).showToast();

        name.value = oldName;
        toggleEditAction();
    });
}

let pendingRequest = false;
async function deleteChecklist() {
    if (pendingRequest) {
        return;
    }

    const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#653d29',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true,
    });

    if (!isConfirmed) {
        return;
    }

    pendingRequest = true;
    try {
        await checklistStore.deleteChecklist();

        window.location.replace('/dashboard');
    } catch (error) {
        Toastify({
            text: error,
        }).showToast();
    }
    pendingRequest = false;
}
</script>

<template>
    <article class="container columns-1">
        <div class="flex justify-between">
            <div class="flex">
                <button v-if="isEditing" @click="saveName" class="bg-orange-800 px-2 h-[40px] rounded font-bold text-sm text-white hover:text-background-contrast hover:bg-orange-600">
                    <i class="fi-br-disk align-middle"></i> SAVE
                </button>
                <button v-else @click="toggleEditAction" class="bg-orange-700 px-2 h-[40px] rounded font-bold text-sm text-white hover:text-background-contrast hover:bg-orange-600">
                    <i class="fi-br-edit align-middle"></i> EDIT
                </button>
                <ChecklistChangeVisibilityButton class="px-2 h-[40px]"></ChecklistChangeVisibilityButton>
            </div>
            <div v-if="checklistStore.isCreatedByMe" class="flex">
                <button @click="deleteChecklist" class="bg-red-500 px-2 h-[40px] rounded font-bold text-sm text-white hover:text-background-contrast hover:bg-red-600">
                    <i class="fi-br-trash align-middle"></i> DELETE
                </button>
            </div>
        </div>
        <div v-if="isEditing" class="flex mt-4">
            <p class="self-center font-bold me-2">Name:</p>
            <input class="rounded-lg w-full ring ring-background-primary hover:ring-orange-500 focus:ring focus:ring-orange-500"
                   ref="nameInput"
                   type="text"
                   v-model.trim="name"
                   @keyup.enter="saveName"
            >
        </div>
        <div v-else class="mt-4">
            <p class="sm:text-xl font-bold">
                {{ name }}
            </p>
        </div>
    </article>
</template>

<style scoped lang="scss">
input {
    border: none;
    &:focus, &:target {
        border: none;
   }
}
</style>
