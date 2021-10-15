<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[
            { title: `Проекты`, url: route('user.projects.index') },
            { title: `Создать` }
        ]"/>
        <div class="bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="store">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input :id="'in1'" v-model="form.name" :error="form.errors.name" label="Название проекта"/>
                    <file-input :id="'in2'" v-model="form.avatar" :error="form.errors.avatar" label="Аватар"/>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                    <loading-button :loading="form.processing" class="btn-indigo" type="submit">Создать проект</loading-button>
                </div>
            </form>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import TextInput from '@/Components/TextInput'
import LoadingButton from '@/Components/LoadingButton'
import Breadcrumbs from "@/Components/Breadcrumbs";
import FileInput from "@/Components/FileInput";

export default {
    components: {
        FileInput,
        Breadcrumbs,
        BreezeAuthenticatedLayout,
        LoadingButton,
        TextInput
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
