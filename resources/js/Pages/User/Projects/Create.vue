<template>
    <breeze-authenticated-layout>
        <div>
            <breadcrumbs :items="[{ title: `Проекты`, url: route('user.projects.index') }, { title: `Создать` }]" />
            <div class="bg-white rounded-md shadow overflow-hidden">
                <form @submit.prevent="store">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                        <text-input :id="'in1'" v-model="form.name" :error="form.errors.name" label="Название проекта"/>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <label class="block">Аватар:</label>
                            <input type="file" @input="form.avatar = $event.target.files[0]">
                        </div>
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
import Breadcrumbs from "@/Components/Breadcrumbs";


export default {
    components: {
        Breadcrumbs,
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
                avatar: null,
            })
        }
    },
    methods: {
        store() {
            this.form.post(this.route('admin.projects.store'))
        },
    }
}
</script>
