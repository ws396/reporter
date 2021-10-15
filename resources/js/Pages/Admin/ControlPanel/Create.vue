<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[
            { title: `Пользователи`, url: route('admin.control-panel.index') },
            { title: `Создать` }
        ]"/>
        <div class="bg-white rounded-md shadow overflow-hidden p-4">
            <breeze-validation-errors class="mb-4"/>

            <form @submit.prevent="submit">
                <div>
                    <breeze-label for="name" value="Имя"/>
                    <breeze-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name"/>
                </div>

                <div class="mt-4">
                    <breeze-label for="email" value="Email"/>
                    <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username"/>
                </div>

                <div class="mt-4">
                    <breeze-label for="password" value="Пароль"/>
                    <breeze-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                                  autocomplete="new-password"/>
                </div>

                <div class="mt-4">
                    <breeze-label for="password_confirmation" value="Подтверждение пароля"/>
                    <breeze-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation"
                                  required autocomplete="new-password"/>
                </div>

                <div class="mt-4">
                    <select-input v-model="form.role" :error="form.errors.role" class="pr-6 w-full lg:w-1/2"
                                  label="Уровень доступа">
                        <option :value="1">Пользователь</option>
                        <option :value="2">Руководитель</option>
                        <option :value="3">Администратор</option>
                    </select-input>
                </div>

                <div class="flex items-center justify-end mt-4">

                    <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Зарегистрировать
                    </breeze-button>
                </div>
            </form>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import BreezeInput from '@/Components/Input'
import BreezeLabel from '@/Components/Label'
import BreezeValidationErrors from '@/Components/ValidationErrors'
import SelectInput from '@/Components/SelectInput'
import Breadcrumbs from "@/Components/Breadcrumbs";

export default {
    components: {
        Breadcrumbs,
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        SelectInput
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: 1,
                terms: false,
            })
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('admin.control-panel.store'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
