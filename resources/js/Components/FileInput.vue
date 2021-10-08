<template>
    <div class="pr-6 pb-8 w-full lg:w-1/2">
        <label v-if="label" class="block" :for="id">{{ label }}</label>
        <input :id="id" type="file" v-bind="$attrs" @input="$emit('update:modelValue', $event.target.files[0])">
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
        type: {
            type: String,
            default: 'text',
        },
        value: Object,
        label: String,
        error: String,
        modelValue: Object,
    }
}
</script>
