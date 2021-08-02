<template>
    <breeze-authenticated-layout>
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
            <label class="block text-gray-700">Trashed:</label>
            <select v-model="form.trashed" class="mt-1 w-full form-select">
                <option :value="null"/>
                <option value="with">With Trashed</option>
                <option value="only">Only Trashed</option>
            </select>
        </search-filter>
        <inertia-link
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            :href="route('user.projects.create')">Добавить проект
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
                                  :href="route('user.projects.edit', project.id)">
                        {{ project.id }}
                        <icon v-if="project.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center" :href="route('user.projects.tasks', project.id)" tabindex="-1">
                        {{ project.name }}
                    </inertia-link>
                </td>
                <td class="border-t">
                    <inertia-link class="px-6 py-4 flex items-center" :href="route('user.projects.tasks', project.id)" tabindex="-1">
                        {{ project.created_at }}
                    </inertia-link>
                </td>
                <td class="border-t w-px">
                    <inertia-link class="px-4 flex items-center" :href="route('user.projects.tasks', project.id)" tabindex="-1">
                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                    </inertia-link>
                </td>
            </tr>
            <tr v-if="projects.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">No projects found.</td>
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
import throttle from 'lodash/throttle'
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
        projects: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(this.route('user.projects'), pickBy(this.form), {preserveState: true})
            }, 150),
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
        console.log(this.$page);
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    }
}
</script>
