import {computed} from "vue";

export default function useInputFieldHelpers(props, emits) {

    const fieldUniqueName = computed(() => `radio_group_field_${props.name}_${props.value}`);
    const updateChecked = () => {
        emits('change', props.value);
    }

    return {
        fieldUniqueName, updateChecked
    };
}