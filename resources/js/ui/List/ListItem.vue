<script setup lang="ts">
import {computed} from "vue";
import useGetFieldHelper from "@/ui/helpers/useGetFieldHelper";

const props = defineProps({
  item: {
    type: Object,
    default: null
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
  selectable: {
    type: Boolean,
    default: false
  },
  clickable: {
    type: Boolean,
    default: false
  },
  selected: {
    type: Boolean,
    default: false
  },
  unread: {
    type: Boolean,
    default: false
  },
  showUnread: {
    type: Boolean,
    default: false
  },
  orderBy: {
    type: String,
    default: 'id'
  }
})

const itemClasses = computed(() => {
  const classes = [];

  // 'cursor-pointer': props.clickable, 'bg-blue-600': props.selected === item

  if (props.clickable) {
    classes.push('cursor-pointer');
  }

  if (props.selected) {
    classes.push('bg-sky-600', 'text-white');
  } else {
    if (props.showUnread && props.unread === true) {
      classes.push('bg-sky-50');
    }
  }

  if (props.selectable) {
    classes.push('px-4');
  } else {
    classes.push('first:pt-0', 'last:pb-0');
  }

  return classes;
})


const { getField: getTitle } = useGetFieldHelper(props.title, props, false);
const { getField: getSubtitle } = useGetFieldHelper(props.subtitle, props, false);
const { getField: getImage } = useGetFieldHelper(props.image, props, false);
</script>

<template>
  <li class="flex py-4 relative" :class="itemClasses">
    <img class="h-10 w-10 rounded-full" :alt="getTitle(item)" :src="getImage(item)" v-if="getImage(item) !== null" />
    <div class="ml-3 overflow-hidden">
      <p class="text-sm font-medium">
        <slot name="title" v-bind="item">{{ getTitle(item) }}</slot>
      </p>
      <p class="text-sm truncate opacity-75">
        {{ getSubtitle(item) }}
      </p>
    </div>
    <div v-if="showUnread && props.unread" class="absolute top-5 right-4 rounded-full w-2 h-2 bg-sky-600"></div>
  </li>
</template>

<style scoped>

</style>
