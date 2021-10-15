<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[
            { title: `Проекты`, url: route('user.projects.index') },
            { title: `Редактировать ${project.name}` }
        ]"/>
        <div class="bg-white rounded-md shadow overflow-hidden">
            <trashed-message v-if="project.deleted_at" class="mb-6" @restore="restore">
                Этот проект был удалён.
            </trashed-message>
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input :id="'in1'" v-model="form.name" :error="form.errors.name" label="Название проекта"/>
                    <file-input :id="'in2'" v-model="form.avatar" :error="form.errors.avatar" label="Аватар"/>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <button v-if="!project.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить
                        проект
                    </button>
                    <loading-button :loading="form.processing" class="btn-indigo" type="submit">Обновить проект</loading-button>
                </div>
            </form>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import TextInput from '@/Components/TextInput'
import LoadingButton from '@/Components/LoadingButton'
import TrashedMessage from '@/Components/TrashedMessage'
import Breadcrumbs from "@/Components/Breadcrumbs"
import FileInput from "@/Components/FileInput";

export default {
    components: {
        FileInput,
        Breadcrumbs,
        BreezeAuthenticatedLayout,
        LoadingButton,
        TextInput,
        TrashedMessage
    },
    remember: 'form', // Inertia.js : Remember local component state data
    props: {
        project: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                name: this.project.name,
                avatar: null,
            })
        }
    },
    methods: {
        update() {
            this.form.post(this.route('admin.projects.update', this.project.id))
        },
        destroy() {
            if (confirm('Вы точно хотите удалить этот проект?')) {
                this.$inertia.delete(this.route('admin.projects.destroy', this.project.id))
            }
        },
        restore() {
            if (confirm('Вы точно хотите восстановить этот проект?')) {
                this.$inertia.put(this.route('admin.projects.restore', this.project.id))
            }
        }
    }
}
</script>
