<script setup>

import { useChecklistStore } from "../stores/checklist";
import { computed } from "vue";
import Swal from "sweetalert2";
import * as Visibility from "../enum/visibility";
import Toastify from "toastify-js";

const checklist = useChecklistStore();

async function showModal() {
    const checklistVisibility = checklist.isPublic ? Visibility.PUBLIC : Visibility.PRIVATE
    const { value } = await Swal.fire({
        title: 'Select visibility for the list',
        input: 'select',
        inputLabel: 'Current value:',
        inputOptions: Visibility.asChoicesForSelect(),
        inputValue: checklistVisibility,
        showCancelButton: true
    });

    if (!value) {
        return;
    }

    Swal.fire('Please wait', '', 'info');

    try {
        let msg = '';
        if (value === Visibility.PUBLIC) {
            await checklist.setAsPublic();
            msg = 'List is public now!';
        } else {
            await checklist.setAsPrivate();
            msg = 'List is private now!';
        }

        Swal.close();
        Toastify({
            text: 'Visibility has been changed. ' + msg
        }).showToast();
    } catch (error) {
        Swal.fire('Error', error, 'error');
    }
}

const iconClass = computed(() => ({
    'fi-br-eye': checklist.isPublic,
    'fi-br-eye-crossed': checklist.isPrivate,
    'align-middle': true,
}));
const label = computed(() => checklist.isPrivate ? 'PRIVATE' : 'PUBLIC');

</script>

<template>
    <button
        @click="showModal"
        class="bg-amber-500 rounded font-bold text-sm text-white hover:text-background-contrast hover:bg-amber-600 ms-2"
    >
        <i :class="iconClass"></i> {{ label }}
    </button>
</template>

<style scoped lang="scss"></style>
