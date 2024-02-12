<script setup>
import useInputFieldHelpers from './input-field-helpers.js'
import {computed, defineEmits, defineProps} from "vue";
import {fieldProps} from './props.js'

const emits = defineEmits(['change'])
const props = defineProps(fieldProps);

const { fieldUniqueName, updateChecked } = useInputFieldHelpers(props, emits);

const fieldClasses = computed(() => {
  const classes = [];
  const variant = props.variant;

  if (variant === 'mobile') {
    classes.push(
        'px-3', 'py-4',
        'hover:bg-slate-50',
        'rounded'
    );
  } else if (variant === 'modern') {
    classes.push(
        'px-3', 'py-4',
        'border', 'border-b-0',
        'first:rounded-t',
        'last:border-b', 'last:rounded-b',
        'cursor-pointer'
    );
  } else {
    classes.push('my-4');
  }

  return classes;
});
</script>

<template>
  <label class="flex" :for="fieldUniqueName" :class="fieldClasses">
    <div class="flex items-center h-5">
      <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600 outline-none"
             :type="props.type"
             :value="props.value"
             :name="name"
             :id="fieldUniqueName"
             :checked="props.selected"
             @change="updateChecked">
    </div>
    <div class="ms-2 text-sm">
      <div>{{ props.label }}</div>
      <p class="opacity-50" v-if="props.description !== null">{{ props.description }}</p>
    </div>
  </label>
</template>

<style scoped>

</style>