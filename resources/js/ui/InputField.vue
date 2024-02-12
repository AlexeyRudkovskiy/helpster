<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "InputField",
    props: [ 'label', 'name', 'type', 'modelValue', 'error', 'errorMessage' ],
    data() {
        return {
            focus: false,
            value: ''
        };
    },
    methods: {
        focusInput() {
            this.focus = true;
        },
        blurInput() {
            this.focus = false;
        }
    },
    computed: {
        isActive() {
            return this.modelValue && this.modelValue.length > 0;
        },
        labelClasses() {
            let styles = this.isActive ? [ '-top-3','px-3', 'py-1', 'font-medium' ] : [ 'top-3' ];

            if (this.error) {
                styles.push('text-red-500');
            } else {
                if (this.focus && this.isActive) {
                    styles.push('text-blue-500');
                } else {
                    styles.push('text-gray-500');
                }
            }

            return styles;
        },
        inputClasses() {
            let styles = [];
            if (this.error) {
                styles.push('border-red-500');
            } else {
                styles.push('focus:border-blue-500');
            }

            return styles;
        }
    }
})
</script>

<template>
    <div class="input-field relative my-4">
        <label :for="name" class="absolute left-4 text-sm font-medium bg-white label" :class="labelClasses">{{ label }}</label>
        <input :type="type" :id="name" class="w-full border-2 rounded px-4 py-2 text-base focus:outline-none"
               :class="inputClasses"
               :value="modelValue"
               @focus.prevent="focusInput"
               @blur.prevent="blurInput"
               @input.prevent="$emit('update:modelValue', $event.target.value)" />
        <template v-if="error && errorMessage && errorMessage.length > 0">
            <div class="text-sm py-2 rounded text-red-500 font-medium">{{ errorMessage }}</div>
        </template>
    </div>
</template>

<style scoped>
.label {
    transition: 0.1s all;
}
</style>
