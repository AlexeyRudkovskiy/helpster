<script>
import {defineComponent, reactive, computed, defineProps} from 'vue'
import Button from "./Button.vue";

export default defineComponent({
    name: "Card",
    components: {Button},
    props: [ 'noContentStyles', 'noHeader', 'noFooter', 'title', 'description', 'footer' ],
    setup(props) {

        const contentClasses = computed(() => {
            const classes = [];

            if (!props.noContentStyles) {
                classes.push('px-6', 'py-4');
            }

            return classes;
        });

        return { contentClasses }
    }
})
</script>

<template>
    <div class="rounded-xl shadow bg-white">

        <div class="rounded-t-xl px-6 py-6 bg-gray-50 border-0 border-b-2 border-gray-100" v-if="!noHeader">
            <div class="text-2xl text-bold ">{{ title }}</div>
            <div class="mt-2 text-gray-600">{{ description }}</div>
        </div>

        <div :class="contentClasses" v-if="!noContentStyles">
            <slot></slot>
        </div>
        <slot v-else></slot>

        <div class="flex w-full rounded-b-xl bg-gray-50 border-0 border-t-2 border-gray-100 p-6" v-if="!noFooter">
            <slot name="footer"></slot>
            <span v-if="!$slots.footer">{{ footer }}</span>
        </div>

    </div>
</template>

<style scoped>

</style>
