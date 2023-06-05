<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex justify-center h-fit">
            <div class="h-1/4 w-full block p-5">
                <h2 class="font-bold text-white text-center sm:w-1/3 mx-auto bg-gray-600 text-3xl relative">
                    {{ 'Word:' }}
                    <button
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-600 hover:text-gray-400 text-white font-bold pr-4 rounded text-center"
                        @click="mute()"
                    >
                        <i v-if="soundOn" class="fas fa-volume-up fa-xs"></i>
                        <i v-else class="fas fa-volume-mute fa-xs"></i>
                    </button>
                </h2><br>
                <div class="text-center">
                    <div class="text-3xl font-bold p-12 inline-flex">
                        {{ randomWord }}
                        <button
                            class="transform -translate-y-1/2 text-blue-400 hover:text-blue-600 font-bold rounded"
                            @click="speak(randomWord)"
                        >
                            <i class="fa fa-play-circle text-xs"></i>
                        </button>
                    </div>
                    <div class="text-3xl text-green-600">
                        {{ showTranslate ? translation : translationDots }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <button
                class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center"
                @click="showTranslation"
            >
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
            translationDots: '',
            soundOn: true
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
            this.createRandomWord();
            if (this.soundOn) {
                this.speak(this.randomWord);
            }
        },
        generateRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].article ? this.words[randomIndex].article + ' ' + this.words[randomIndex].word : this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();

            if (this.soundOn) {
                this.speak(this.randomWord);
            }

            this.showTranslate = false;
        },
        displayDots() {
            this.translationDots = this.translation.replace(/[^\s,.\p{P}]/gu, "*");
        },
        speak(text) {
            window.speechSynthesis.cancel();
            let utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'de-DE';
            utterance.pitch = 0.9;
            utterance.rate = 0.8;
            utterance.volume = 0.5;
            window.speechSynthesis.speak(utterance);
        },
        mute() {
            this.soundOn = !this.soundOn;

            if (!this.soundOn) {
                window.speechSynthesis.cancel();
            }
        },
        createRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].article ? this.words[randomIndex].article + ' ' + this.words[randomIndex].word : this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();
        }
    }
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '@fortawesome/fontawesome-free/css/all.css';

defineProps(['words']);
</script>

<style scoped>
</style>
