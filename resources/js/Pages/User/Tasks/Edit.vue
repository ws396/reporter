<template>
    <breeze-authenticated-layout>
        <div>
            <h1 class="mb-8 font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('user.tasks')">Задачи</inertia-link>
                <span class="text-indigo-400 font-medium"> /</span> Редактировать
            </h1>
            <div class="bg-white rounded-md shadow overflow-hidden">
                <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
                  This task has been deleted.
                </trashed-message>
                <form @submit.prevent="update">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">

                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <label class="block">Task Start:</label>
                            <flat-pickr v-model="form.task_start" :config="fpconfig"
                                        :class="[{ error: form.errors.task_start }, 'w-full']"></flat-pickr>
                            <div v-if="form.errors.task_start" class="form-error">{{ form.errors.task_start }}</div>
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <label class="block">Task End:</label>
                            <flat-pickr v-model="form.task_end" :config="fpconfig" :class="[{ error: form.errors.task_end }, 'w-full']"></flat-pickr>
                            <div v-if="form.errors.task_end" class="form-error">{{ form.errors.task_end }}</div>
                        </div>

                        <select-input :id="'in3'" v-model="form.project" :error="form.errors.project" label="Project">
                            <option :value="null"/>
                            <option value="CA">Canada</option>
                            <option value="US">United States</option>
                        </select-input>
                        <text-input :id="'in4'" v-model="form.task_description" :error="form.errors.task_description" label="Task Desc"/>
                        <text-input :id="'in5'" v-model="form.task_worktime" :error="form.errors.task_worktime" label="Worktime"/>
                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                        <button v-if="!task.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить
                            задачу
                        </button>
                        <loading-button :loading="form.processing" class="btn-indigo" type="submit">Обновить задачу</loading-button>
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
        task: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                task_start: this.task.task_start,
                task_end: this.task.task_end,
                project: this.task.project,
                task_description: this.task.task_description,
                task_worktime: this.task.task_worktime,
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
      this.form.put(this.route('user.tasks.update', this.task.id))
    },
    destroy() {
      if (confirm('Are you sure you want to delete this organization?')) {
        this.$inertia.delete(this.route('user.tasks.destroy', this.task.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this organization?')) {
        this.$inertia.put(this.route('user.tasks.restore', this.task.id))
      }
    },
    }
}
</script>
