<template>
    <Head title="Dashboard" />

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
                        class="text-black-600 sm:text-3xl"
                    >
                        {{ item.word }}
                    </li>
                </ul>
            </div>
            <div class="w-1/4 max-w-screen-md">
                <h2 class="font-bold text-white text-center bg-gray-600 sm:text-3xl">{{ 'Translation:' }}</h2>
                <ul>
                    <li
                        v-for="(item, index) in words" :key="item.id"
                        :class="index % 2 === 0 ? 'bg-gray-200' : false"
                        class="text-black-600 sm:text-3xl"
                    >
                        {{ item.translation }}
                    </li>
                </ul>
            </div>
            <div class="w-1/4 max-w-screen-md">
                <h2 class="font-bold text-white text-center bg-gray-600 sm:text-3xl">{{ 'Action:' }}</h2>
                <ul>
                    <li
                        v-for="(item, index) in words" :key="item.id"
                        :class="index % 2 === 0 ? 'bg-gray-200' : false"
                        class="text-black-600 sm:text-3xl"
                    >
                        <button
                            id="show-modal"
                            class="block w-full px-4 py-1 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out"
                            @click="editing = true"
                        >
                            {{ 'Edit' }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import {ref} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps(['words']);
const editing = ref(false);

</script>

<script>
export default {
    name: "AddedWords"
}
</script>

<style scoped>

</style>
