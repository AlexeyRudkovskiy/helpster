<script setup>
import {computed} from "vue";

const props = defineProps([ 'darker' ])

const classes = computed(() => {
    if (props.darker) {
        return [ 'bg-sky-800', 'text-white' ];
    }

    return [ 'bg-sky-600' ];
});
</script>

<template>
<div class="px-8 flex items-center h-16" :class="classes">
    <!-- Search bar -->
    <div>
        <div class="rounded py-2 px-3 w-96 bg-sky-500 shadow shadow-sky-600 hover:shadow-sky-700 text-sm search-box">Type something...</div>
    </div>
    <!-- End search bar -->
</div>
</template>

<style scoped>
.search-box {
    color: rgba(0, 0, 0, .75);
}
</style>
