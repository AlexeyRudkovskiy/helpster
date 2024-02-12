<script setup>

import Toolbar from "./Toolbar.vue";
</script>

<template>
<div class="w-80 h-full bg-slate-200 sidebar-container">
<!--    <Toolbar :darker="true"></Toolbar>-->
    <div class="px-6 py-4 pt-6">
        <slot></slot>
    </div>
</div>
</template>

<style scoped>
.sidebar-container {

}
</style>
