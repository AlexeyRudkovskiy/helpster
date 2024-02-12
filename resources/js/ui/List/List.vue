<script setup>
import {defineProps, defineEmits, useSlots, computed} from 'vue'
import ListItem from "@/ui/List/ListItem.vue";
import _ from 'underscore'

const emits = defineEmits([ 'clicked' ]);
const props = defineProps({
  data: {
    type: Array,
    default: []
  },
  title: {
    type: String,
    default: 'title'
  },
  subtitle: {
    type: String,
    default: 'subtitle'
  },
  image: {
    type: String,
    default: 'image'
  },
  clickable: {
    type: Boolean,
    default: false
  },
  selectable: {
    type: Boolean,
    default: false
  },
  selected: {
    default: null
  },
  showUnread: {
    type: Boolean,
    default: false
  }
});
const slots = useSlots();

const onClick = (item) => {
  if (!props.clickable) {
    return ;
  }
  emits('clicked', item)
};

</script>

<template>
  <ul role="list" class="divide-y divide-slate-200">
    <!-- Remove top/bottom padding when first/last child -->
    <ListItem v-for="item in data"
              @click="onClick(item)"
              :key="item.id"
              :item="item"
              :selected="props.selected === item"
              :selectable="props.selectable"
              :show-unread="props.showUnread"
              :clickable="props.clickable"
              :unread="item.read === 0"
              :title="props.title"
              :subtitle="props.subtitle"
              :image="props.image"
    ></ListItem>
    <slot name="empty" v-if="data.length === 0"></slot>
  </ul>

</template>

<style scoped>

</style>
