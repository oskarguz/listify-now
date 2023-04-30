<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    id: String,
    description: String,
    position: Number,
    checked: Boolean,
});

const id = ref(props.id || null);
const description = ref(props.description || '');
const position = ref(props.position || 0);
const checked = ref(props.checked);

const isNewItem = computed(() => {
    return !id.value;
});

function onTextAreaInput(e) {
    let textArea = e.target;

    description.value = textArea.value;

    adjustTextareaHeight(textArea);
}

function adjustTextareaHeight(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

function deleteItem() {
    // @TODO delete item
}

function addItem() {
    // @TODO save item
}
</script>

<template>
    <div class="bg-gray-300 flex rounded-lg overflow-hidden">
        <div class="p-2 flex-grow flex">
            <input v-model="checked" type="checkbox" class="p-3 me-2">
            <div class="flex-grow content-grow">
                <textarea @input="onTextAreaInput"
                          :value="(props.description || '')"
                          class="w-full"
                          rows="1">
                </textarea>
            </div>
        </div>
        <button v-if="!isNewItem" @click="deleteItem" class="bg-red-500 w-[40px] text-xl">
            <i class="fi-br-trash"></i>
        </button>
        <button v-if="isNewItem" @click="addItem" class="bg-green-500 w-[40px] text-xl">
            <i class="fi-br-plus"></i>
        </button>
    </div>
</template>

<style scoped lang="scss">
textarea {
    background: none;
    border: none;
    resize: none;
    padding: 0 10px;
    &:focus, &:target {
        border: none;
        --tw-ring-color: none;
    }
}
input[type="checkbox"] {
    border: none;
    border-radius: 25%;
    background-color: #909090;
    --tw-ring-color: none;
    &:checked {
        background-color: #909090;
    }
}
</style>
