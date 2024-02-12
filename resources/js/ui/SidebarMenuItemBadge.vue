<script setup>
const props = defineProps({
    value: {
        type: String,
        default: 0
    }
})
</script>

<template>
    <div class="rounded-full px-3 py-1 absolute badge bg-sky-600 text-white right-3 font-medium">{{ props.value }}</div>
</template>

<style scoped>

</style>
