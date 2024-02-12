<script setup>
import {defineProps, defineEmits} from 'vue'
import {groupProps} from './props.js'
import useGroup from './useGroup'
import useGetFieldHelper from "@/ui/helpers/useGetFieldHelper";
import RadioField from "@/ui/RadioGroup/RadioField.vue";

const emits = defineEmits([ 'update:modelValue' ]);
const props = defineProps(groupProps);

const { updateSelected } = useGroup(props, emits);
const { getField: getKey } = useGetFieldHelper(props.value, props, true);
const { getField: getLabel } = useGetFieldHelper(props.label, props);
const { getField: getDescription } = useGetFieldHelper('description', props);

</script>

<template>
<div>
  <RadioField v-for="option in props.options"
              :key="getKey(option)"
              :value="getKey(option)"
              :label="getLabel(option)"
              :name="props.name"
              :selected="props.modelValue === getKey(option)"
              :description="getDescription(option)"
              :variant="props.variant"
              @change="updateSelected(getKey(option))"
  ></RadioField>
</div>
</template>

<style scoped>

</style>