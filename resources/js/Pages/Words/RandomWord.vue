<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex justify-center h-fit">
            <div class="h-1/4 w-full block p-5">
                <h2 class="font-bold text-white text-center sm:w-1/3 mx-auto bg-gray-600 text-3xl">{{ 'Word:' }}</h2><br>
                <div class="text-center">
                    <p class="text-3xl font-bold p-12">
                        {{ randomWord }}
                    </p>
                    <p class="text-3xl text-green-600">
                        {{ showTranslate ? translation : translationDots }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <button class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center" @click="showTranslation">
                {{ button }}
            </button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "RandomWord",
    data() {
        return {
            showTranslate: false,
            randomWord: '',
            translation: '',
            button: 'Show Translation',
            translationDots: ''
        }
    },
    mounted() {
        this.startRandomWord();
    },
    methods: {
        showTranslation(){
            if(this.showTranslate === false) {
                this.showTranslate = true;
                this.button = 'Next Word';
                this.showTranslate = true;
            } else {
                this.button = 'Show Translation';
                this.generateRandomWord();
            }
        },
        startRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();
        },
        generateRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();
            this.showTranslate = false;
        },
        displayDots() {
            this.translationDots = this.translation.replace(/[^\s,.\p{P}]/gu, "*");
        }
    }
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps(['words']);
</script>

<style scoped>
</style>
