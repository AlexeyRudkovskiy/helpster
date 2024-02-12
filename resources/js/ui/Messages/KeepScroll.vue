<script setup>
import {ref, defineProps, onMounted, onBeforeUpdate, watch} from 'vue'

const props = defineProps([ 'id', 'container' ]);

const scrollContent = ref(null);
const scrollContainer = ref(null);
const scrollPosition = ref(0);

onMounted(() => {
  scrollContainer.value = props.container;

  initScroll();
});

watch(() => props.id, () => {
  initScroll();
})

const initScroll = () => {
  const scrollHeight = scrollContainer.value.scrollHeight;
  scrollContainer.value.scrollTo(0, scrollHeight);
}
</script>

<template>
<div ref="scrollContent"><slot></slot></div>
</template>

<style scoped>

</style>
