<template>
    <div class="pr-6 pb-8 w-full lg:w-1/2">
        <label v-if="label" class="block" :for="id">{{ label }}:</label>
        <textarea :id="id" ref="input" v-bind="$attrs" class="form-input w-full" :class="{ error: error }" :type="type" :value="modelValue"
               @input="$emit('update:modelValue', $event.target.value)"/>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        type: {
            type: String,
            default: 'text',
        },
        value: String,
        label: String,
        error: String,
        modelValue: String,
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
