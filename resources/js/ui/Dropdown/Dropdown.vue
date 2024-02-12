<script setup>

</script>

<template>
<div class="relative">
    <!-- Selected item -->
    <div class="p-3 rounded-xl hover:shadow">
        <slot></slot>
    </div>
    <!-- End selected item -->

    <!-- Dropdown options -->
    <div class="absolute top-0 left-3 dropdown-options bg-white border border-gray-300 rounded rounded-xl p-4">
        Dropdown options
    </div>
    <!-- End dropdown options -->
</div>
</template>

<style scoped>
.dropdown-options {
    top: 100%;
    margin-top: 12px;
}
</style>
