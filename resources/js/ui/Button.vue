<script>
import {defineComponent} from 'vue'
import MoonLoader from 'vue-spinner/src/MoonLoader.vue'

export default defineComponent({
    name: "Button",
    components: {MoonLoader},
    props: [ 'gray', 'success', 'danger', 'loading', 'disabled', 'size' ],
    computed: {
        classes() {
            let classes = [];

            const size = this.size;
            if (size === 'small') {
              classes.push('py-1', 'px-2', 'text-xs');
            } else {
              classes.push('py-3', 'px-4');
            }

            if (this.gray) {
                if (!this.loading) {
                    classes.push('bg-gray-400', 'hover:bg-gray-500');
                } else {
                    classes.push('bg-gray-300');
                }
            } else if (this.success) {
                if (!this.loading) {
                    classes.push('bg-sky-600', 'hover:bg-sky-700');
                } else {
                    classes.push('bg-blue-400');
                }
            } else if (this.danger) {
              if (!this.loading) {
                classes.push('bg-red-600', 'hover:bg-red-700');
              } else {
                classes.push('bg-red-400');
              }
            } else {
                if (!this.loading) {
                    classes.push('bg-gray-700', 'hover:bg-gray-800');
                } else {
                    classes.push('bg-gray-500');
                }
            }

            return classes;
        }
    }
})
</script>

<template>
<div class="text-center  rounded cursor-pointer text-white relative" :class="classes">
    <slot v-if="!loading"></slot>
    <template v-if="loading">
        <span class="select-none">&nbsp;</span>
        <moon-loader :size="'24px'" class="spinner" color="#f8fafc"></moon-loader>
    </template>
</div>
</template>

<style scoped>
.spinner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
}
</style>
