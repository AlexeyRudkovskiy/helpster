<script setup>
import {defineProps, ref, computed, onMounted, defineEmits} from 'vue'

const emits = defineEmits([ 'update:modelValue' ]);
const props = defineProps({
  options: {
    type: Array,
    default: []
  },
  placeholder: {
    type: String,
    default: 'Please select an option'
  },
  modelValue: {
    default: null
  },
  value: {
    type: String,
    default: 'id'
  },
  label: {
    type: String,
    default: 'title'
  },
  numericValue: {
    type: Boolean,
    default: false
  }
});

const getKey = (option) => {
  let value = option[props.value];
  return props.numericValue ? Number(value) : value;
}

const getLabel = (option) => {
  return option[props.label];
}

const updateSelected = ($event) => {
  let value = $event.target.value;
  emits('update:modelValue', props.numericValue ? Number(value) : value);
};

</script>

<template>
  <div class="input-field my-4 relative">
    <select class="select-none flex w-full border-2 rounded px-4 py-2 text-base focus:outline-none hover:cursor-pointer"
            @change="updateSelected">
      <option v-for="option in options"
              :selected="getKey(option) === modelValue"
              :key="getKey(option)"
              :value="getKey(option)">{{ getLabel(option) }}</option>
    </select>
  </div>
</template>

<style scoped>
.border-placeholder {
  margin-bottom: calc(1rem + 2px);
}
</style>