<script setup>
import ChecklistNameSection from "./ChecklistNameSection.vue";
import ChecklistItemsSection from "./ChecklistItemsSection.vue";
import ChecklistCopyLink from "./ChecklistCopyLink.vue";
import { useChecklistStore } from "../stores/checklist";
import ChecklistCreateButton from "./ChecklistCreateButton.vue";
import { useAuthStore } from "../stores/auth";

const store = useChecklistStore();
const authStore = useAuthStore();
authStore.refreshState();

let appElement = document.getElementById('app');
if (appElement) {
    let checklist;
    try {
        checklist = JSON.parse(appElement.dataset.checklist);
    } catch (e) {
        checklist = null;
    }
    if (checklist) {
        store.init(checklist);
        delete appElement.dataset.checklist;
    }
}

</script>

<template>
    <section class="container columns-1 mx-auto mt-8 px-4">
        <ChecklistNameSection></ChecklistNameSection>
        <div class="w-full border border-black my-5"></div>
        <ChecklistItemsSection></ChecklistItemsSection>

        <p v-if="!authStore.isLogged" class="mt-4 bg-background-contrast rounded-lg p-2">
            The list will be public, anyone with a link can access and edit,<br/>
            If you want to access all features, <a href="/login" class="font-bold">log in</a>
        </p>

        <ChecklistCopyLink v-if="store.id"></ChecklistCopyLink>
        <ChecklistCreateButton v-if="!store.id"></ChecklistCreateButton>
    </section>
</template>

<style scoped>

</style>
