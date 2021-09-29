<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[{ title: `Пользователи` }]" />
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" :placeholder="'Фильтр по имени'">
            <label class="block text-gray-700">Удалённые:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">С удалёнными</option>
                <option value="only">Только удалённые</option>
            </select>
        </search-filter>
        <inertia-link
            v-if="can.create_user"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            :href="route('admin.control-panel.create')">Добавить пользователя
        </inertia-link>


        <table v-if="can.edit_user" class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4">Id</th>
                <th class="px-6 pt-6 pb-4">Имя</th>
                <th class="px-6 pt-6 pb-4">Создан</th>
                <th class="px-6 pt-6 pb-4">Уровень доступа</th>
            </tr>

            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500"
                                  :href="route('admin.control-panel.edit', user.id)">
                        {{ user.id }}
                        <icon v-if="user.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('admin.control-panel.edit', user.id)" tabindex="-1">
                        {{ user.name }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('admin.control-panel.edit', user.id)" tabindex="-1">
                        {{ user.created_at }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('admin.control-panel.edit', user.id)" tabindex="-1">
                        {{ user.role }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <div class="items-center justify-center">
                        <inertia-link
                            class="px-3 py-2 border border-gray-200 rounded-md hover:border-gray-300"
                                      :href="route('export.index', user.id)" tabindex="-1">
                            Экспорт
                        </inertia-link>
                    </div>
                </td>
                <td class="border-t w-px">
                    <inertia-link class="px-4 flex items-center" :href="route('admin.control-panel.edit', user.id)"
                                  tabindex="-1">
                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                    </inertia-link>
                </td>
            </tr>
            <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Пользователи не найдены.</td>
            </tr>
        </table>

        <table v-else class="w-full whitespace-nowrap">
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
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ user.role }}
                    </div>
                </td>
            </tr>
            <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Пользователи не найдены.</td>
            </tr>
        </table>


        <pagination-ping class="mt-6" :links="users.links"/>


    </breeze-authenticated-layout>

</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import PaginationPing from '@/Components/PaginationPing'
import Icon from '@/Components/Icon'
import SearchFilter from '@/Components/SearchFilter'

import pickBy from 'lodash/pickBy'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import Breadcrumbs from "@/Components/Breadcrumbs";

export default {
    components: {
        Breadcrumbs,
        BreezeAuthenticatedLayout,
        BreezeButton,
        PaginationPing,
        SearchFilter,
        Icon
    },
    props: {
        filters: Object,
        users: Object,
        can: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(function () {
                this.$inertia.get(this.route('admin.control-panel.index'), pickBy(this.form), {preserveState: true, only: ['users']})
            }, 500),
        },
    },
    data: function () {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            }
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    }
}
</script>
