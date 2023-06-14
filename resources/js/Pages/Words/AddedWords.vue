<template>
    <Head title="Added Words" />

    <AuthenticatedLayout>
        <div class=" text-black-600 font-semibold sm:text-lg text-center">
            <span>{{ 'Number Of Added Words: ' }} {{ words.length }}</span>
        </div>
        <div class="flex justify-center items-center">
            <div class="w-1/4 max-w-screen-md">
                <h2 class="font-bold text-white text-center bg-gray-600 sm:text-3xl">{{ 'Word:' }}</h2>
                <ul>
                    <li
                        v-for="(item, index) in words" :key="item.id"
                        :class="index % 2 === 0 ? 'bg-gray-200' : false"
                        class="text-black-600 sm:text-3xl text-center"
                    >
                        {{ item.article ? item.article + ' ' + item.word : item.word }}
                    </li>
                </ul>
            </div>
            <div class="w-1/4 max-w-screen-md">
                <h2 class="font-bold text-white text-center bg-gray-600 sm:text-3xl">{{ 'Translation:' }}</h2>
                <ul>
                    <li
                        v-for="(item, index) in words" :key="item.id"
                        :class="index % 2 === 0 ? 'bg-gray-200' : false"
                        class="text-black-600 sm:text-3xl text-center"
                    >
                        {{ item.translation }}
                    </li>
                </ul>
            </div>
            <div class="w-auto max-w-screen-md">
                <h2 class="font-bold text-white text-center bg-gray-600 sm:text-3xl">
                    {{ 'Action:' }}
                </h2>
                <ul>
                    <li
                        v-for="(item, index) in words" :key="item.id"
                        :class="index % 2 === 0 ? 'bg-gray-200' : false"
                        class="text-black-600 sm:text-3xl"
                    >
                        <button
                            v-if="done"
                            id="show-modal"
                            class="px-4 text-left text-sm bg-gray-300 leading-5 text-black-700 hover:bg-blue-400 transition duration-150 ease-in-out mr-1"
                            @click="openModal(item); editing = true"
                        >
                            {{ 'Edit' }}
                        </button>
                        <button
                            @click="destroy(item.id)"
                            class="px-4 text-left text-sm bg-gray-300 leading-5 text-black-700 hover:bg-red-400 transition duration-150 ease-in-out"
                        >
                            {{ 'Delete' }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="modal" v-if="editing">
            <div class="modal-content">
                <h1 class="text-center">Change Word Settings</h1>
                <form @submit.prevent="form.put(route('words.update', selectedItemId), { onSuccess: () => editing = false })">
                    <input type="text" v-model="selectedItemName" class="text-center mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-if="selectedItemName !== undefined" />
                    <input type="text" v-model="selectedItemTranslation" class="text-center mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-if="selectedItemTranslation !== undefined" />

                    <div class="flex justify-between">
                        <button class="mt-4 text-left bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-blue-600" @click="editing = false; form.reset(); form.clearErrors()">Cancel</button>
                        <button class="mt-4 text-right bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-green-600" type="submit" @click="editing = false; form.reset(); form.clearErrors();">Save</button>
                    </div>
                </form>Action
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';

defineProps(['words']);
const editing = ref(false);

const form = useForm({
    selectedItemName: '',
    selectedItemTranslation: '',
});
</script>

<script>
export default {
    name: "AddedWords",
    data() {
        return {
            selectedItemId: false,
            done: false
        }
    },
    methods: {
        async destroy(itemId) {
            await axios.delete(`/words/${itemId}`)
                .then(response => {
                    window.location.reload()
                    console.log('The entry was successfully deleted.');
                })
                .catch(error => {
                    console.error('Error while deleting record', error);
                });
        },
        openModal(item) {
            if (item && item.word) {
                this.selectedItemId = item.id;
                this.selectedItemName = item.word;
                this.selectedItemTranslation = item.translation;
            }
        }
    }
}
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
}
</style>
