<template>
    <breeze-authenticated-layout>
        <div>
            <h1 class="mb-8 font-bold text-3xl">
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('user.projects.tasks', project.id)">
                    Задачи {{ project.name }}
                </inertia-link>
                <span class="text-indigo-400 font-medium"> /</span> Редактировать
            </h1>
            <div class="w-full mb-4">
                <div>
                    <h1>Участники задачи:</h1>
                    <div v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        {{ user.name }}
                    </div>
                    <inertia-link
                        v-if="can.invite_to_task"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                        :href="route('admin.projects.tasks.invite', [project.id, task.id])">Добавить участника
                    </inertia-link>
                </div>
            </div>
            <div class="bg-white rounded-md shadow overflow-hidden">
                <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
                    Эта задача была удалена {{ task.deleted_at }}
                </trashed-message>
                <form @submit.prevent="update">
                    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                        <div class="pr-6 pb-8 w-full">
                            <div>
                                Создатель задачи: {{ taskgiver.name }} ({{ task.created_at }})
                            </div>
                            <div v-if="task.last_editor">
                                Последний редактировавший: {{ taskgiver.name }} ({{ task.updated_at }})
                            </div>
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <label class="block">Начало задачи:</label>
                            <flat-pickr v-model="form.task_start" :config="fpconfig"
                                        :class="[{ error: form.errors.task_start }, 'w-full']"></flat-pickr>
                            <div v-if="form.errors.task_start" class="form-error">{{ form.errors.task_start }}</div>
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <label class="block">Конец задачи:</label>
                            <flat-pickr v-model="form.task_end" :config="fpconfig" :class="[{ error: form.errors.task_end }, 'w-full']"></flat-pickr>
                            <div v-if="form.errors.task_end" class="form-error">{{ form.errors.task_end }}</div>
                        </div>

                        <text-input :id="'in3'" v-model="form.task_worktime" :error="form.errors.task_worktime" label="Время работы"
                                    placeholder="-- ч. -- мин."
                                    v-maska="{ mask: '#* ч. @# мин.', tokens: { '@': { pattern: /[0-5]/ }} }"/>
                        <select-input v-model="form.task_status" :error="form.errors.task_status" class="pr-6 w-full lg:w-1/2"
                                      label="Статус задачи">
                            <option :value="0">Поставлена</option>
                            <option :value="1">Начата</option>
                            <option :value="2">Выполнена</option>
                        </select-input>
                        <text-area :id="'in4'" v-model="form.task_description" :error="form.errors.task_description" label="Описание задачи"/>

                    </div>
                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
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
import TextArea from '@/Components/TextArea'
import SelectInput from '@/Components/SelectInput'
import LoadingButton from '@/Components/LoadingButton'
import TrashedMessage from '@/Components/TrashedMessage'
import {maska} from 'maska'


import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import {Russian} from 'flatpickr/dist/l10n/ru.js'


export default {
    directives: {maska},
    components: {
        BreezeAuthenticatedLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        TextArea,
        flatPickr,
        TrashedMessage
    },
    remember: 'form', // Inertia.js : Remember local component state data
    props: {
        task: Object,
        taskgiver: Object,
        project: Object,
        users: Object,
        can: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                task_start: this.task.task_start,
                task_end: this.task.task_end,
                task_status: this.task.task_status,
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
            this.form.put(this.route('user.projects.tasks.update', [this.project.id, this.task.id]))
        },
        destroy() {
            if (confirm('Вы точно хотите удалить эту задачу?')) {
                this.$inertia.delete(this.route('user.projects.tasks.destroy', [this.project.id, this.task.id]))
            }
        },
        restore() {
            if (confirm('Вы точно хотите восстановить эту задачу?')) {
                this.$inertia.put(this.route('user.projects.tasks.restore', [this.project.id, this.task.id]))
            }
        },
    }
}
</script>
