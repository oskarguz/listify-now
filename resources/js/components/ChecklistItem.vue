<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useChecklistStore } from "../stores/checklist";
import Swal from "sweetalert2";

const UPDATE_DESCRIPTION_DELAY_IN_SECONDS = 1;

const store = useChecklistStore();
const textAreaInp = ref(null);

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

const isNewItem = computed(() => !id.value);

let timer = null;
function onTextAreaInput(e) {
    if (timer) {
        clearTimeout(timer);
    }

    if (isNewItem.value) {
        description.value = textAreaInp.value.value;
    } else {
        timer = setTimeout(() => {
            description.value = textAreaInp.value.value;
        }, UPDATE_DESCRIPTION_DELAY_IN_SECONDS * 1000);
    }

    adjustTextareaHeight(textAreaInp.value);
}

function adjustTextareaHeight(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

function deleteItem() {
    store.deleteItem(id.value).then((result) => {
        console.log(result.message);
    }).catch((error) => {
        console.error(error.message);
    })
}

function addItem() {
    if (!description.value.trim()) {
        console.error('Item description is empty');
        return;
    }

    store.createItem(description.value, checked.value).then((result) => {
        console.log(result.message);
    }).catch((error) => {
        console.error(error.message);
    });
    resetValues();
}

function resetValues() {
    id.value = props.id || null
    description.value = props.description || '';
    position.value = props.position || 0;
    checked.value = props.checked;

    textAreaInp.value.focus();
}

onMounted(() => {
    adjustTextareaHeight(textAreaInp.value);
});

watch(description, async (newDescription, oldDescription) => {
    if (newDescription.trim() === oldDescription.trim()) {
        return;
    }
    if (!id.value) {
        return;
    }

    store.updateItemDescription(id.value, newDescription).then((result) => {
        console.log(result.message);
    }).catch((error) => {
        console.error(error.message);
    });
});

watch(checked, async (newChecked, oldChecked) => {
    if (newChecked === oldChecked) {
        return;
    }
    if (!id.value) {
        return;
    }

    store.updateItemChecked(id.value, newChecked).then((result) => {
        console.log(result.message);
    }).catch((error) => {
        Swal.fire('Error', error, 'error');
    });
});
</script>

<template>
    <div class="bg-white flex rounded-lg overflow-hidden">
        <div class="p-2 flex-grow flex">
            <input v-model="checked" type="checkbox" class="p-3 me-2">
            <div class="flex-grow content-grow">
                <textarea @input="onTextAreaInput"
                          :value="(description || '')"
                          ref="textAreaInp"
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
