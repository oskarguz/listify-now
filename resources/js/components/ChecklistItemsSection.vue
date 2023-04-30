<script setup>

import ChecklistItem from "./ChecklistItem.vue";

// testing
const items = [{
    id: 'abcdefghijklmn',
    description: 'Lorem ipsum',
    checked: true,
}];

</script>

<template>
    <ChecklistItem v-for="item in items"
        v-bind="item"
        :key="item.id"
        class="mb-3"
    ></ChecklistItem>

    <ChecklistItem :item="null"></ChecklistItem>
</template>

<style scoped lang="scss">

</style>
