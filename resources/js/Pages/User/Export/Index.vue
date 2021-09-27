<template>
    <breeze-authenticated-layout>
        <h1 class="mb-8 font-bold text-3xl">
            Экспорт
        </h1>
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" :placeholder="'Фильтр по проекту'">
            <label class="block text-gray-700">Удалённые:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">С удалёнными</option>
                <option value="only">Только удалённые</option>
            </select>
        </search-filter>
        <a
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            :href="route('export.launch', {user: user.id, search: this.form.search, trashed: this.form.trashed})">Экспортировать в Excel
        </a>
        <h1 class="mt-4 font-bold text-xl">Задачи пользователя {{ user.name }}</h1>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4">Id</th>
                <th class="px-6 pt-6 pb-4">Проект</th>
                <th class="px-6 pt-6 pb-4">Создано</th>
                <th class="px-6 pt-6 pb-4">Начато</th>
            </tr>
            <tr v-for="task in tasks.data" :key="task.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center focus:text-indigo-500">
                        {{ task.id }}
                        <icon v-if="task.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </div>
                </td>
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ task.project }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ task.created_at }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="px-6 py-4 flex items-center">
                        {{ task.task_start }}
                    </div>
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

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        PaginationPing,
        SearchFilter,
        Icon
    },
    props: {
        filters: Object,
        tasks: Object,
        user: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(function () {
                this.$inertia.get(this.route('export.index', this.user.id), pickBy(this.form), {preserveState: true, only: ['tasks']})
            }, 1000),
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
