<template>
    <breeze-authenticated-layout>
        <breadcrumbs :items="[
            { title: `Проекты`, url: route('user.projects.index') },
            { title: `Задачи ${project.name}` }
        ]" />
        <div class="w-full mb-4">
            <div>
                <h1>Участники проекта:</h1>
                <div v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    {{ user.name }}
                </div>
                <inertia-link
                    v-if="can.invite_to_project"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                    :href="route('admin.projects.invite', project.id)">Добавить участника
                </inertia-link>
            </div>
        </div>
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" :placeholder="'Фильтр по ID'">
            <label class="block text-gray-700">Удалённые:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">С удалёнными</option>
                <option value="only">Только удалённые</option>
            </select>
        </search-filter>
        <inertia-link
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            :href="route('user.projects.tasks.create', project.id)">Добавить задачу
        </inertia-link>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4">Id</th>
                <th class="px-6 pt-6 pb-4">Создано</th>
                <th class="px-6 pt-6 pb-4">Начато</th>
                <th class="px-6 pt-6 pb-4">Проработано</th>
                <th class="px-6 pt-6 pb-4">Статус</th>
            </tr>
            <tr v-for="task in tasks.data" :key="task.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500"
                                  :href="route('user.projects.tasks.edit', [project.id, task.id])">
                        {{ task.id }}
                        <icon v-if="task.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.edit', [project.id, task.id])" tabindex="-1">
                        {{ task.created_at }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.edit', [project.id, task.id])" tabindex="-1">
                        {{ task.task_start }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.edit', [project.id, task.id])" tabindex="-1">
                        {{ task.task_worktime }}
                    </inertia-link>
                </td>
                 <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center"
                                  :href="route('user.projects.tasks.edit', [project.id, task.id])" tabindex="-1">
                        {{ statusFrontend[task.task_status] }}
                    </inertia-link>
                </td>
                <td class="border-t w-px">
                    <inertia-link class="px-4 flex items-center" :href="route('user.projects.tasks.edit', [project.id, task.id])"
                                  tabindex="-1">
                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                    </inertia-link>
                </td>
            </tr>
            <tr v-if="tasks.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">Задачи не найдены.</td>
            </tr>
        </table>
        <pagination-ping class="mt-6" :links="tasks.links"/>


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
        tasks: Object,
        project: Object,
        users: Object,
        can: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(function () {
                this.$inertia.get(this.route('user.projects.tasks.index', this.project.id), pickBy(this.form), {preserveState: true, only: ['tasks']})
            }, 500),
        }
    },
    data: function () {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
            statusFrontend: {
                0: "Поставлена",
                1: "Начата",
                2: "Выполнена",
            } // При необходимости можно перенести например в лэйаут
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    }
}
</script>
