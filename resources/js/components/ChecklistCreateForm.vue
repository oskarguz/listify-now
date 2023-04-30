<script setup>
import ChecklistNameSection from "./ChecklistNameSection.vue";
import ChecklistItemsSection from "./ChecklistItemsSection.vue";
import ChecklistCopyLink from "./ChecklistCopyLink.vue";
</script>

<template>
    <div class="mx-auto mt-8 px-4">
        <ChecklistNameSection></ChecklistNameSection>
        <div class="w-full border border-cyan-900 my-5"></div>
        <ChecklistItemsSection></ChecklistItemsSection>

<!--   @TODO check whether user is auth    -->
        <p class="mt-4 bg-gray-200 rounded-lg p-2">
            The list will be public, anyone with a link can access and edit,<br/>
            If you want to access all features, <span class="font-bold">log in</span>
        </p>

        <ChecklistCopyLink></ChecklistCopyLink>
    </div>
</template>

<style scoped>

</style>
