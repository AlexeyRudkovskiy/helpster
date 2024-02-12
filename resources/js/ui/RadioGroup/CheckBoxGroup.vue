<script setup>
import {defineProps, defineEmits} from 'vue'
import {groupProps} from './props.js'
import useGetFieldHelper from "@/ui/helpers/useGetFieldHelper";
import RadioField from "@/ui/RadioGroup/RadioField.vue";

const emits = defineEmits([ 'update:modelValue' ]);
const props = defineProps(groupProps);

const { getField: getKey } = useGetFieldHelper(props.value, props);
const { getField: getLabel } = useGetFieldHelper(props.label, props);
const { getField: getDescription } = useGetFieldHelper('description', props);

const updateSelected = (value) => {
  const updatedValue = [...props.modelValue];

  if (updatedValue.indexOf(value) > -1) {
    updatedValue.splice(updatedValue.indexOf(value), 1);
  } else {
    updatedValue.push(value);
  }

  emits('update:modelValue', updatedValue);
}
</script>

<template>
<div>
  <RadioField v-for="option in props.options"
              :type="'checkbox'"
              :key="getKey(option)"
              :value="getKey(option)"
              :label="getLabel(option)"
              :name="props.name"
              :selected="props.modelValue.indexOf(getKey(option)) > -1"
              :description="getDescription(option)"
              :variant="props.variant"
              @change="updateSelected(getKey(option))"
  ></RadioField>
</div>
</template>

<style scoped>

</style>