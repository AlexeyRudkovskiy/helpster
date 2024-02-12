<script setup>
import {defineProps, defineEmits, useSlots, ref, onBeforeUpdate} from 'vue'
import Button from "@/ui/Button.vue";
import Step from "@/ui/Stepper/Step.vue";

const emits = defineEmits([ 'next', 'finish', 'navigate' ]);
const props = defineProps({
  activeStep: {
    type: Number,
    default: 0
  }
});
const slots = useSlots();
const steps = ref(slots.default().filter(child => child.type === Step));

onBeforeUpdate(() => {
  steps.value = slots.default().filter(child => child.type === Step);
});

const getCircleClasses = (step) => {
  const classes = [];

  if (step.props['is-active'] || step.props['is-complete']) {
    classes.push('bg-blue-600', 'text-white', 'border-blue-600')
  }

  return classes;
}

const getStepTabClasses = (step) => {
  const classes = [];

  if (step.props['is-active'] || step.props['is-complete']) {
    classes.push('text-blue-600')
  }

  return classes;
}

const nextIconClasses = (step) => {
  return step.props['is-complete'] ? [ 'text-blue-600' ] : [ 'text-gray-500' ];
}

const labelClasses = (step) => {
  return step.props['is-complete'] ? [ 'hover:bg-blue-50', 'cursor-pointer' ] : [];
}

const onNextPage = () => {
  emits('next');
}

const onFinish = () => {
  emits('finish');
}

const goToStep = (step, index) => {
  if (step.props['is-complete']) {
    emits('navigate', index);
  }
}
</script>

<template>
<div>
  <ol class="flex items-center w-full space-x-4 font-medium text-center text-gray-500 mb-2 -ml-3">
    <li class="flex items-center" v-for="(step, index) in steps" :class="getStepTabClasses(step)">
      <div class="flex items-center py-3 px-4 rounded" :class="labelClasses(step)" @click="goToStep(step, index)">
        <span class="flex items-center justify-center w-6 h-6 me-2 text-sm border rounded-full shrink-0" :class="getCircleClasses(step)">
            {{ index + 1 }}
        </span>
        <div>{{ step.props.title }}</div>
      </div>
      <svg class="w-3 h-3 ms-2 sm:ms-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10"
           :class="nextIconClasses(step)"
           v-if="index < steps.length - 1">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
      </svg>
    </li>
  </ol>

  <div class="w-96">
    <slot></slot>

    <div class="mt-4">
      <slot name="footer" v-bind="{ onNextPage, onFinish, active: props.activeStep, steps: steps.length }">
        <Button @click="onNextPage" v-if="props.activeStep < steps.length - 1">Next</Button>
        <Button @click="onFinish" v-if="props.activeStep === steps.length - 1">Finish</Button>
      </slot>
    </div>
  </div>
</div>
</template>

<style scoped>

</style>