<script setup>
const props = defineProps({
    title: {
        type: String,
        default: 'Group name'
    }
})
</script>

<template>
<div class="mt-6 first:mt-0">
    <div class="text-sm text-gray-500 px-4 py-2">{{ props.title }}</div>
    <slot></slot>
</div>
</template>

<style scoped>

</style>
