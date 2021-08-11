<template>
    <div class="pr-6 pb-8 w-full lg:w-1/2">
        <label v-if="label" class="block" :for="id">{{ label }}:</label>
        <select :id="id" ref="input" v-model="selected" :value="modelValue" v-bind="$attrs" class="form-select w-full" :class="{ error: error }">
            <slot/>
        </select>
        <input-error v-if="error">{{ error }}</input-error>
    </div>
</template>

<script>
import InputError from "@/Components/InputError";
export default {
    components: {InputError},
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        //value: [String, Number, Boolean],
        modelValue: [String, Number, Boolean],
        label: String,
        error: String,
    },
    data() {
        return {
            selected: this.modelValue,
        }
    },
    watch: {
        selected(selected) {
            this.$emit('input', selected)
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
    },
}
</script>
