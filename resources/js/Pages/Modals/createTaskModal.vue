<template>
    <modal-layout>

        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:mr-4 sm:text-left">
                    <h2 class="text-2xl leading-6 font-medium text-gray-900" id="modal-title">
                        Добавление задачи
                    </h2>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Здесь вы можете добавить задачу
                        </p>
                    </div>
                    <div class="mt-5">
                        <label class="text-lg leading-6 font-medium text-gray-900">Название проекта</label>
                        <input type="text" class="rounded-md border-gray-300 w-full block" v-model="project"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2
                            bg-purple-300 text-base font-medium text-white hover:bg-purple-400 focus:outline-none focus:ring-2
                            focus:ring-offset-2 focus:ring-purple-400 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="createTask()"
            >
                Отправить
            </button>
            <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4
                            py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2
                            focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="$emit('closemodal')"
            >
                Закрыть
            </button>
        </div>

    </modal-layout>
</template>

<script>
import ModalLayout from '@/Layouts/ModalLayout';

export default {
    components: {
        ModalLayout
    },
    data: function () {
        return {
            project: "",
        }
    },
    methods: {
        createTask() {
            axios.post('/user/create-task', {project: this.project})
                .then(response => {
                    console.log(response);
                    $emit('closemodal');
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>
