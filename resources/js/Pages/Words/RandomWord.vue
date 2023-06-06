<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex justify-between mt-1 h-1/4 mx-auto sm:w-1/3 relative">
            <div class="flex">
                <div class="flex w-14 my-1 ml-1">
                    <input
                        id="language-toggle"
                        v-model="languageSwitching"
                        @change="toggleLanguage"
                        type="checkbox"
                        class="hidden"
                    />
                    <label
                        for="language-toggle"
                        class="toggle-label"
                    >
                        <span class="toggle-track bg-gray-400 rounded-full w-10 h-5 flex items-center">
                            <span
                                class="toggle-thumb bg-white rounded-full w-4 h-4 shadow-md transform transition-transform duration-300"
                                :class="{'translate-x-7': languageSwitching}"
                            />
                        </span>
                    </label>
                    <div class="ml-2">
                    <span class="text-xs">
                        {{ 'Lang' }}
                    </span>
                    </div>
                </div>
            </div>
            <div class="flex">
                <button
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black-600 hover:text-gray-400 text-green-600 font-bold pr-4 rounded text-center"
                    @click="mute()"
                >
                    <i v-if="soundOn" class="fas fa-volume-up fa-xs"></i>
                    <i v-else class="fas fa-volume-mute fa-xs text-red-600"></i>
                </button>
            </div>
        </div>
        <div class="flex justify-center h-fit">
            <div class="h-1/4 w-full block">
                <h2 class="font-bold text-white text-center sm:w-1/3 mx-auto bg-gray-600 text-3xl relative">
                    {{ 'Word:' }}
                </h2><br>
                <div class="text-center">
                    <div class="text-3xl font-bold p-12 inline-flex">
                        {{ languageSwitching ? randomWord : translation }}
                        <button
                            class="transform -translate-y-1/2 text-blue-400 hover:text-blue-600 font-bold rounded"
                            @click="speak(languageSwitching ? randomWord : translation)"
                        >
                            <i class="fa fa-play-circle text-xs"></i>
                        </button>
                    </div>
                    <div class="text-3xl text-green-600">
                        <span v-if="languageSwitching">
                            {{ showTranslate ? translation : translationDots }}
                        </span>
                        <span v-else>
                            {{ showTranslate ? randomWord : randomDots }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center mt-4">
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
            soundOn: true,
            languageSwitching: true
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
                if (this.languageSwitching) {
                    this.speak(this.randomWord)
                } else {
                    this.speak(this.translation);
                }
            }
        },
        generateRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].article ? this.words[randomIndex].article + ' ' + this.words[randomIndex].word : this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();

            if (this.soundOn) {
                if (this.languageSwitching) {
                    this.speak(this.randomWord)
                } else {
                    this.speak(this.translation);
                }
            }

            this.showTranslate = false;
        },
        displayDots() {
            this.translationDots = this.translation.replace(/[^\s,.\p{P}]/gu, "*");
            this.randomDots = this.randomWord.replace(/[^\s,.\p{P}]/gu, "*");
        },
        speak(text) {
            window.speechSynthesis.cancel();
            let utterance = new SpeechSynthesisUtterance(text);

            if (this.languageSwitching) {
                utterance.lang = 'de-DE';

            } else {
                utterance.lang = 'ru-RU';
            }

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
        },
        createTranslationRandomWord() {
            const randomIndex = Math.floor(Math.random() * this.words.length);
            this.randomWord = this.words[randomIndex].article ? this.words[randomIndex].article + ' ' + this.words[randomIndex].word : this.words[randomIndex].word;
            this.translation = this.words[randomIndex].translation;
            this.displayDots();
        },
        toggleLanguage() {
            if (this.languageSwitching) {
                this.createRandomWord();
            } else {
                this.createTranslationRandomWord();
            }
        },
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
