<script setup lang="ts">
const props = defineProps({
  previous: {
    type: String,
    default: 'Назад'
  },
  next: {
    type: String,
    default: 'Далі'
  },
  totalPages: {
    type: Number,
    default: 0
  },
  page: {
    type: Number,
    default: 0
  }
})
const emits = defineEmits([ 'previous', 'next' ]);

const previousPage = () => {
  if (props.page > 1) {
    emits('previous');
  }
}

const nextPage = () => {
  if (props.page < props.totalPages) {
    emits('next');
  }
}
</script>

<template>
<div class="flex items-center">
  <div class="mr-4 text-sm">{{ props.page }} / {{ props.totalPages }}</div>
  <div class="space-x-2">
    <a class="rounded border px-3 py-2 text-gray-700 text-sm cursor-pointer" @click="previousPage">{{ props.previous }}</a>
    <a class="rounded border px-3 py-2 text-gray-700 text-sm cursor-pointer" @click="nextPage">{{ props.next }}</a>
  </div>
</div>
</template>

<style scoped>

</style>
