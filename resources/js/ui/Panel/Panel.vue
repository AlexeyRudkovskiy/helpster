<script setup>
import {computed, defineProps} from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Panel Title'
    },
    noBodyPaddings: {
        type: Boolean,
        default: false
    },
    isTableContent: {
        type: Boolean,
        default: false
    }
})

const bodyClasses = computed(() => {
   const classes = [];

   if (!props.noBodyPaddings) {
       classes.push('px-6', 'py-6');
   }

   return classes;
});

const headerClasses = computed(() => {
    const classes = [];

    if (!props.isTableContent) {
        classes.push('border-0', 'border-b-2', 'border-gray-100');
    }

    return classes;
});

</script>

<template>
<div class="bg-white rounded-xl shadow">

    <div class="rounded-t-xl px-6 py-6 bg-gray-50">
        <div class="text-bold">{{ props.title }}</div>
    </div>

    <div class="rounded" :class="bodyClasses">
        <slot></slot>
    </div>
</div>
</template>

<style scoped>

</style>
