<script setup>
import {defineProps, defineExpose, ref, watch} from 'vue'
import Check from 'vue-material-design-icons/Check.vue'

const props = defineProps({
  name: {
    type: String,
    default: 'step'
  },
  index: {
    type: Number
  },
  title: {
    type: String,
    default: 'Step Title'
  },
  isActive: {
    type: Boolean,
    default: false
  },
  isComplete: {
    type: Boolean,
    default: false
  }
});
</script>

<template>
  <div v-if="isActive" class="py-2">
    <slot></slot>
  </div>
</template>

<style scoped>

</style>