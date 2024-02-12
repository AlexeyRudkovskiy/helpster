<script setup>
import {computed, defineProps} from 'vue'

const props = defineProps({
  myMessage: {
    type: Boolean,
    default: false
  }
});

const messageClasses = computed(() => {
  return props.myMessage
    ? [ 'bg-slate-300', 'text-slate-600', 'rounded-r-none', 'group-first:rounded-tr-xl', 'group-last:rounded-br-xl' ]
    : [ 'bg-sky-600', 'text-white', 'rounded-l-none', 'group-first:rounded-tl-xl', 'group-last:rounded-bl-xl' ];
});

const messageContainerClasses = computed(() => {
  return props.myMessage
    ? [ 'justify-end' ]
    : [];
});
</script>

<template>
<div class="flex group" :class="messageContainerClasses">
  <div class="p-2 inline-block px-3 rounded-xl max-w-96" :class="messageClasses"><slot></slot></div>
</div>
</template>

<style scoped>

</style>
