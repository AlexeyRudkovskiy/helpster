<script setup>
defineProps([ 'contentClasses' ])
</script>

<template>
    <div class="flex pb-4 border-b-2 border-gray-100 last:border-b-0">
        <slot name="icon"></slot>
        <div class="w-full flex flex-col" :class="contentClasses">
            <slot></slot>
        </div>
    </div>
</template>

<style scoped>

</style>
