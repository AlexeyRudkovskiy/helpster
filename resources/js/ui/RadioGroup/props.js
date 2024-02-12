const groupProps = {
    options: {
        type: Array,
        default: []
    },
    variant: {
        type: String,
        default: 'classic'
    },
    value: {
        type: String,
        default: 'id'
    },
    modelValue: {
        default: null
    },
    label: {
        type: String,
        default: 'title'
    },
    name: {
        type: String,
        default: 'radio_group'
    },
    numericValue: {
        type: Boolean,
        default: false
    }
};

const fieldProps = {
    type: {
        type: String,
        default: 'radio'
    },
    variant: {
        type: String,
        default: 'classic'
    },
    value: {
        default: null
    },
    label: {
        type: String,
        default: 'Option Label'
    },
    description: {
        type: String,
        default: null
    },
    name: {
        type: String,
        default: 'radio_group'
    },
    selected: {
        type: Boolean,
        default: false
    }
};

export { groupProps, fieldProps };