<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[
            { title: `Проекты`, url: route('user.projects.index') },
            { title: `Задачи ${project.name}`, url: route('user.projects.tasks.index', project.id) },
            { title: `Создать` }
        ]"/>
        <div class="bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="store">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">

                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="block">Начало задачи:</label>
                        <flat-pickr v-model="form.task_start" :config="fpconfig"
                                    :class="[{ error: form.errors.task_start }, 'w-full']"></flat-pickr>
                        <input-error v-if="form.errors.task_start">{{ form.errors.task_start }}</input-error>
                    </div>
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="block">Конец задачи:</label>
                        <flat-pickr v-model="form.task_end" :config="fpconfig" :class="[{ error: form.errors.task_end }, 'w-full']"></flat-pickr>
                        <input-error v-if="form.errors.task_end">{{ form.errors.task_end }}</input-error>
                    </div>

                    <text-input :id="'in3'" v-model="form.task_worktime" :error="form.errors.task_worktime" label="Время работы"
                                placeholder="-- ч. -- мин."
                                v-maska="{ mask: '#* ч. @# мин.', tokens: { '@': { pattern: /[0-5]/ }} }"/>
                    <select-input v-model="form.task_status" :error="form.errors.task_status" class="pr-6 w-full lg:w-1/2"
                                  label="Статус задачи">
                        <option :value="0">Поставлена</option>
                        <option :value="1">Начата</option>
                        <option :value="2" selected>Выполнена</option>
                    </select-input>
                    <text-area :id="'in4'" v-model="form.task_description" :error="form.errors.task_description" label="Описание задачи"/>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                    <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать задачу</loading-button>
                </div>
            </form>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import TextInput from '@/Components/TextInput'
import TextArea from '@/Components/TextArea'
import SelectInput from '@/Components/SelectInput'
import LoadingButton from '@/Components/LoadingButton'
import {maska} from 'maska'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import {Russian} from 'flatpickr/dist/l10n/ru.js'
import InputError from "@/Components/InputError"
import Breadcrumbs from "@/Components/Breadcrumbs"

export default {
    directives: {maska},
    components: {
        Breadcrumbs,
        InputError,
        BreezeAuthenticatedLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        TextArea,
        flatPickr
    },
    remember: 'form', // Inertia.js : Remember local component state data
    props: {
        project: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                task_start: null,
                task_end: null,
                task_status: 0,
                task_description: null,
                task_worktime: null,
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
            this.form.post(this.route('user.projects.tasks.store', this.project.id))
        },
    }
}
</script>
