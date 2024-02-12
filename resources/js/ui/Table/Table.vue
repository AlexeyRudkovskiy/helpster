<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    repeatKey: {
        type: String,
        default: 'id'
    },
    items: {
        type: Array,
        default: []
    },
    columns: {
        type: Array,
        default: []
    }
})
</script>

<template>
<table class="w-full items-center bg-transparent w-full border-collapse">
    <thead>
    <tr>
        <th v-for="column in props.columns" :key="column.key"
            class="px-6 bg-gray-50 text-gray-500 align-middle border border-solid border-gray-100 py-3 uppercase border-t-0 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
            :class="column.classes"
        >
          <slot v-bind:name="`header_${column.key}`" v-bind="column">
            {{ column.label }}
          </slot>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in props.items" :key="item[props.repeatKey]">
        <td v-for="column in props.columns"
            class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 text-left text-gray-700 "
        >
            <slot v-bind:name="column.key" v-bind="item">{{ item[column.key] }}</slot>
        </td>
    </tr>
    </tbody>
</table>
</template>

<style scoped>

</style>
