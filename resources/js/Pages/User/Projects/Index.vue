<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[{ title: `Проекты` }]" />
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" :placeholder="'Фильтр по названию'">
            <label class="block text-gray-700">Удалённые:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">С удалёнными</option>
                <option value="only">Только удалённые</option>
            </select>
        </search-filter>
        <inertia-link
            v-if="can.create_project"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            :href="route('admin.projects.create')">Добавить проект
        </inertia-link>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4">Id</th>
                <th class="px-6 pt-6 pb-4">Проект</th>
                <th class="px-6 pt-6 pb-4">Создано</th>
            </tr>
            <tr v-for="project in projects.data" :key="project.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500"
                                  :href="route('user.projects.tasks.index', project.id)">
                        {{ project.id }}
                        <icon v-if="project.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.index', project.id)" tabindex="-1">
                        <div class="mr-1 project-avatar flex justify-center items-center">
                            <img :src="project.avatar" />
                        </div>
                        {{ project.name }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.index', project.id)" tabindex="-1">
                        {{ project.created_at }}
                    </inertia-link>
                </td>
                <td v-if="can.edit_project" class="border-t">
                    <div class="items-center justify-center">
                        <inertia-link
                            class="px-3 py-2 border border-gray-200 rounded-md hover:border-gray-300"
                                      :href="route('admin.projects.edit', project.id)" tabindex="-1">
                            Редактировать
                        </inertia-link>
                    </div>
                </td>
                <td class="border-t w-px">
                    <inertia-link class="px-4 flex items-center" :href="route('user.projects.tasks.index', project.id)" tabindex="-1">
                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                    </inertia-link>
                </td>
            </tr>
            <tr v-if="projects.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Проекты не найдены.</td>
            </tr>
        </table>
        <pagination-ping class="mt-6" :links="projects.links"/>


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
        projects: Object,
        can: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(function () {
                this.$inertia.get(this.route('user.projects.index'), pickBy(this.form), {preserveState: true, only: ['projects']})
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
    created() {
        console.log(this.projects);
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    }
}
</script>
