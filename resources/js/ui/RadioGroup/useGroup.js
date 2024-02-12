export default function useGroup(props, emits) {
    const getKey = (option) => {
        let value = option[props.value];
        return props.numericValue ? Number(value) : value;
    }

    const getDescription = (option) => typeof option.description !== "undefined" ? option.description : null;

    const getLabel = (option) => option[props.label];

    const updateSelected = (value) => emits('update:modelValue', value);

    return {
        getKey, getDescription, getLabel, updateSelected
    }
}