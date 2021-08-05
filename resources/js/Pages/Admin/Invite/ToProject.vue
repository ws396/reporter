<template>
    <breeze-authenticated-layout>
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('user.projects.tasks', project.id)">
                {{ project.name }}
            </inertia-link>
            <span class="text-indigo-400 font-medium"> /</span> Добавить к проекту
        </h1>
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
            <label class="block text-gray-700">Trashed:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">With Trashed</option>
                <option value="only">Only Trashed</option>
            </select>
        </search-filter>
        <div
            class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            @click="invite()">Добавить пользователя
        </div>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4">Id</th>
                <th class="px-6 pt-6 pb-4">Имя</th>
                <th class="px-6 pt-6 pb-4">Создан</th>
                <th class="px-6 pt-6 pb-4">Уровень доступа</th>
            </tr>
            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center focus:text-indigo-500">
                        {{ user.id }}
                        <icon v-if="user.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </div>
                </td>
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ user.name }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ user.created_at }}
                    </div>
                </td>
                <td class="border-t w-px">
                    <div class="px-4 flex items-center">
                        <input type="checkbox" :value="user.id" v-model="this.formInvite.picked_users"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                    </div>
                </td>
            </tr>
            <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">No users found.</td>
            </tr>
        </table>
        <pagination-ping class="mt-6" :links="users.links"/>


    </breeze-authenticated-layout>

</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import BreezeCheckbox from '@/Components/Checkbox'
import PaginationPing from '@/Components/PaginationPing'
import Icon from '@/Components/Icon'
import SearchFilter from '@/Components/SearchFilter'

import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeCheckbox,
        PaginationPing,
        SearchFilter,
        Icon
    },
    props: {
        filters: Object,
        users: Object,
        project: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(this.route('user.projects.invite', this.project.id), pickBy(this.form), {preserveState: true})
            }, 150),
        }
    },
    data: function () {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
            formInvite: this.$inertia.form({
                picked_users: [],
            })
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        invite() {
            this.formInvite.post(this.route('user.projects.invite-store', this.project.id), this.formInvite.pickedUsers);
        }
    }
}
</script>
