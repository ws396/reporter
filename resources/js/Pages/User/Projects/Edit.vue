<template>
    <breeze-authenticated-layout>
        <div>
            <h1 class="mb-8 font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('user.projects.index')">Проекты</inertia-link>
                <span class="text-indigo-400 font-medium"> /</span> Редактировать
            </h1>
            <div class="bg-white rounded-md shadow overflow-hidden">
                <trashed-message v-if="project.deleted_at" class="mb-6" @restore="restore">
                    Этот проект был удалён.
                </trashed-message>
                <form @submit.prevent="update">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                        <text-input :id="'in1'" v-model="form.name" :error="form.errors.name" label="Название проекта"/>
                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <button v-if="!project.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить
                            проект
                        </button>
                        <loading-button :loading="form.processing" class="btn-indigo" type="submit">Обновить проект</loading-button>
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
import TrashedMessage from '@/Components/TrashedMessage'


import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import {Russian} from 'flatpickr/dist/l10n/ru.js'


export default {
    components: {
        BreezeAuthenticatedLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        flatPickr,
        TrashedMessage
    },
    remember: 'form', // Inertia.js : Remember local component state data
    props: {
        project: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.project.name,
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
        update() {
            this.form.put(this.route('admin.projects.update', this.project.id))
        },
        destroy() {
            if (confirm('Вы точно хотите удалить этот проект??')) {
                this.$inertia.delete(this.route('admin.projects.destroy', this.project.id))
            }
        },
        restore() {
            if (confirm('Вы точно хотите восстановить этот проект?')) {
                this.$inertia.put(this.route('admin.projects.restore', this.project.id))
            }
        },
    }
}
</script>
