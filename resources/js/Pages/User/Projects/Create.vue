<template>
    <breeze-authenticated-layout>
        <div>
            <h1 class="mb-8 font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('user.projects')">Проекты</inertia-link>
                <span class="text-indigo-400 font-medium"> /</span> Создать
            </h1>
            <div class="bg-white rounded-md shadow overflow-hidden">
                <form @submit.prevent="store">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                        <text-input :id="'in1'" v-model="form.name" :error="form.errors.name" label="Project name"/>
                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                        <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать проект</loading-button>
                    </div>
                </form>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

import TextInput from '@/Components/TextInput'
import SelectInput from '@/Components/SelectInput'
import LoadingButton from '@/Components/LoadingButton'


import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import {Russian} from 'flatpickr/dist/l10n/ru.js'


export default {
    components: {
        BreezeAuthenticatedLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        flatPickr
    },
    remember: 'form', // Inertia.js : Remember local component state data
    data() {
        return {
            form: this.$inertia.form({
                name: null,
            }),
            fpconfig: {
                dateFormat: 'd-m-Y H:i',
                locale: Russian,
                enableTime: true,
                time_24hr: true
            },
        }
    },
    methods: {
        store() {
            this.form.post(this.route('admin.projects.store'))
        },
    }
}
</script>
