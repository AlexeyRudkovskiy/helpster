<script setup>
import {useSlots} from 'vue'

const slots = useSlots();

const hasSidebar = () => {
    return typeof slots.sidebar !== "undefined";
}
</script>

<template>
<div class="app-container flex min-h-screen">
    <div v-if="hasSidebar()" class="sidebar min-h-screen">
        <slot name="sidebar"></slot>
    </div>

    <div class="w-full shrink grow-0">
        <slot></slot>
    </div>
</div>
</template>

<style scoped>

</style>
