<script setup>
import {computed} from "vue";
import SidebarMenuItemBadge from "./SidebarMenuItemBadge.vue";

const props = defineProps({
    badge: {
        type: String,
        default: ''
    }
});

const hasBadge = computed(() => {
    return props.badge.length > 0;
});
</script>

<template>
    <a href="javascript:" class="rounded-xl flex px-4 py-3 text-gray-600 hover:bg-slate-100 hover:text-black hover:shadow relative">
        <slot></slot>

        <div v-if="hasBadge">
            <SidebarMenuItemBadge :value="props.badge" class="badge"></SidebarMenuItemBadge>
        </div>
    </a>
</template>

<style scoped>
.badge {
    font-size: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
}
</style>
