<template>
    <Head title="Game: Word Filling" />

    <AuthenticatedLayout>
        <div class="flex justify-center h-fit mt-4 mb-4">
            <select
                v-model="selectedFilter"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-28"
                @change="generateRandomWord"
            >
                <option value="all-time">All Time</option>
                <option value="day">Day</option>
                <option value="threeDays">3 Days</option>
                <option value="week">7 Days</option>
                <option value="month">31 Days</option>
            </select>
        </div>
        <div
            v-if="filteredWords.length !== 0"
            class="flex justify-between mt-1 h-1/4 mx-auto sm:w-1/3 relative"
        >
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
                        title="Click to switch language for displayed words."
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

            <div class="flex flex-wrap">
                <div class="flex-1">
                    <button
                        class="bg-black-600 hover:text-gray-400 text-green-600 font-bold pr-4 rounded text-center"
                        @click="mute()"
                        title="Turns the pronunciation of words on and off."
                    >
                        <i v-if="soundOn" class="fas fa-volume-up fa-xs"></i>
                        <i v-else class="fas fa-volume-mute fa-xs text-red-600"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex justify-center h-fit">
            <div class="h-1/4 w-full block">
                <h2
                    v-if="filteredWords.length !== 0"
                    class="font-bold text-white text-center sm:w-1/3 mx-auto bg-gray-600 text-3xl relative"
                >
                    {{ 'Word:' }}
                </h2><br>
                <div
                    v-if="filteredWords.length !== 0"
                    class="text-center"
                >
                    <div class="text-3xl font-bold p-4 inline-flex">
                        {{ languageSwitching ? randomWord : translation }}
                        <button
                            class="transform -translate-y-1/2 text-blue-400 hover:text-blue-600 font-bold rounded"
                            @click="speak(languageSwitching ? randomWord : translation)"
                            title="Listen to the word."
                        >
                            <i class="fa fa-play-circle text-xs"></i>
                        </button>
                    </div>
                    <div class="text-3xl font-bold p-4 flex-col">
                        <span class="text-3xl text-green-600 mb-4">
                            <span v-if="!showTranslateFilled && languageSwitching">
                                {{ translationDots }}
                            </span>
                            <span v-else-if="!showTranslateFilled && !languageSwitching">
                                {{ randomDots }}
                            </span>
                            <span v-else-if="showTranslateFilled && languageSwitching">
                                {{ translation }}
                            </span>
                            <span v-else-if="showTranslateFilled && !languageSwitching">
                                {{ randomWord }}
                            </span>
                        </span>
                    </div>
                    <div class="pt-6">
                        <span
                            v-if="isEntered && !correctInput"
                            class="mt-16"
                        >
                            {{ 'Your Answer:' }}
                        </span><br>
                        <span
                            v-if="isEntered"
                            class="text-3xl font-bold"
                        >
                            <span
                                v-html="showTranslateFilled ? translationFilled : translationDots"
                                :class="{'text-green-500 mx-auto text-3xl text-green-600 mb-4': correctInput && showTranslate, 'text-red-300 mx-auto': !correctInput && showTranslate}"
                            ></span>
                        </span>
                        <span
                            v-if="languageSwitching"
                            class="flex flex-col mt-4"
                        >
                            <input
                                v-if="!isEntered"
                                v-model="userWordFilled"
                                placeholder="Enter..."
                                class="w-auto mb-4 mx-auto px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                                type="text"
                                @keyup.enter="checkFilling(translation)"
                            />
                            <button
                                v-if="!showTranslateFilled"
                                class="mx-auto px-4 py-2 pb-3 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                                @click="checkFilling(translation)"
                            >
                                {{ 'Check' }}
                            </button>

                            <span
                                class="text mt-4"
                                :class="{'bg-green-300 mx-auto': correctInput && showTranslate, 'bg-red-300 mx-auto': !correctInput && showTranslate}"
                            >
                                {{ correctInput && showTranslate ? wordsForCongratulationsAndFailures(arrayWordsForCongratulations) : !correctInput && showTranslate ? wordsForCongratulationsAndFailures(arrayWordsOfFailure) : '' }}
                            </span>
                        </span>
                        <span
                            v-else
                            class="flex flex-col mt-4"
                        >
                            <input
                                v-if="!isEntered"
                                v-model="userWordFilled"
                                placeholder="Enter..."
                                class="w-auto mb-4 mx-auto px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                                type="text"
                                @keyup.enter="checkFilling(randomWord)"
                            />
                            <button
                                v-if="!showTranslateFilled"
                                class="mx-auto px-4 py-2 pb-3 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                                @click="checkFilling(randomWord)"
                            >
                                {{ 'Check' }}
                            </button>
                        </span>
                    </div>
                    <div
                        v-if="filteredWords.length !== 0 && (showTranslate || showTranslateFilled)"
                        class="flex justify-center mt-12"
                    >
                        <button
                            class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center"
                            @click="showTranslation"
                        >
                            {{ button }}
                        </button>
                    </div>
                </div>
                <div
                    v-else
                    class="text-center"
                >
                    <div class="text-3xl font-bold p-12 inline-flex">
                        {{ 'You have no words added for the selected date.' }}
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="filteredWords.length !== 0"
            class="flex justify-between mt-32 h-1/4 mx-auto sm:w-1/3 relative"
        >
            <div class="flex w-1/2 pl-2">
                <button
                    class="hover:text-black text-orange-800 bg-orange-200 border border-orange-800 mr-2 px-1 rounded justify-center flex items-center shadow-lg w-36 h-12"
                    @click="notDisplayWord('remembered')"
                    title="Click to stop this word from appearing in the list."
                    @mouseenter="isHoveredRemembered = true"
                    @mouseleave="isHoveredRemembered = false"
                >
                    <span>
                        {{ 'Remembered!' }}
                        <i v-show="isHoveredRemembered" class="fas fa-check-square" style="color: orange;"></i>
                    </span>
                </button>
            </div>
            <div class="flex w-1/2 justify-end">
                <button
                    class="hover:text-black text-green-800 bg-green-200 border border-green-800 mr-2 px-1 rounded justify-center flex items-center shadow-lg w-36 h-12"
                    @click="notDisplayWord('learned')"
                    title="Click to stop this word from appearing in the list."
                    @mouseenter="isHoveredLearned = true"
                    @mouseleave="isHoveredLearned = false"
                >
                    <span>
                        {{ 'Learned!' }}
                        <i v-show="isHoveredLearned" class="fas fa-check-square" style="color: green;"></i>
                    </span>
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "WordFilling",
    data() {
        return {
            showTranslate: false,
            randomWord: '',
            translation: '',
            button: 'Next',
            translationDots: '',
            soundOn: true,
            languageSwitching: true,
            isHoveredLearned: false,
            isHoveredRemembered: false,
            currentWord: '',
            selectedFilter: 'all-time',
            userWordFilled: '',
            correctInput: false,
            arrayWordsForCongratulations: [
                'Good job!',
                'Great work!',
                'Excellent!',
                'You did a fantastic job!',
                'Superb!',
                'Well done!',
                'Amazing!',
                'You did an outstanding job!',
                'You achieved perfection!',
                'Great result!'
            ],
            arrayWordsOfFailure: [
                'Don\'t be discouraged!',
                'Try again!',
                'Failure is a part of the process.',
                'It\'s not a success, but it\'s not the end.',
                'Failure is an opportunity for growth.',
                'Don\'t be afraid to make mistakes.',
                'Learn from your mistakes.',
                'It\'s important not to give up.',
                'Keep moving forward.',
                'I\'m confident you\'ll succeed next time!'
            ],
            translationFilled: '',
            showTranslateFilled: '',
            isEntered: false,
            randomDots: ''
        }
    },
    mounted() {
        this.startRandomWord();
    },
    computed: {
        filteredWords() {
            const today = new Date();
            switch (this.selectedFilter) {
                case 'all-time':
                    return this.visibleWords;
                case 'day':
                    return this.visibleWords.filter(word => {
                        const daysAgo = new Date().setDate(today.getDate() - 1);

                        return new Date(word.created_at) >= daysAgo
                    });
                case 'threeDays':
                    return this.visibleWords.filter(word => {
                        const threeDaysAgo = new Date().setDate(today.getDate() - 3);

                        return new Date(word.created_at) >= threeDaysAgo
                    });
                case 'week':
                    return this.visibleWords.filter(word => {
                        const weekAgo = new Date().setDate(today.getDate() - 7);

                        return new Date(word.created_at) >= weekAgo
                    });
                case 'month':
                    return this.visibleWords.filter(word => {
                        const monthAgo = new Date().setDate(today.getDate() - 31);

                        return new Date(word.created_at) >= monthAgo
                    });
                default:
                    return this.visibleWords;
            }
        },
    },
    methods: {
        showTranslation(){
            this.isEntered = false;
            this.button = 'Next Word';
            this.translationFilled = '';
            if(this.showTranslate === false) {
                this.showTranslate = true;
            } else {
                this.correctInput = false;
                this.showTranslateFilled = false;
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
            this.createRandomWord();

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
            if (this.filteredWords.length === 0) { return false; }

            const randomIndex = Math.floor(Math.random() * this.filteredWords.length);
            this.currentWord = this.filteredWords[randomIndex];
            this.randomWord = this.filteredWords[randomIndex].article ? this.filteredWords[randomIndex].article + ' ' + this.filteredWords[randomIndex].word : this.filteredWords[randomIndex].word;
            this.translation = this.filteredWords[randomIndex].translation;
            this.displayDots();
        },
        toggleLanguage() {
            this.showTranslate = '';
            this.translationFilled = '';
            this.showTranslateFilled = '';
            this.isEntered = false;
            this.userWordFilled = '';
            this.correctInput = false;
            this.createRandomWord();
        },
        async notDisplayWord(memorizationLevel) {
            await axios.get(`/words/not-display-word/${this.currentWord.id}`,{
                params: {
                    memorizationLevel: memorizationLevel
                }
            }).then(response => {
                window.location.reload()
                console.log('The entry was successfully added.');
            })
                .catch(error => {
                    console.error('Error while adding record', error);
                });
        },
        checkFilling(correctFilling) {
            this.isEntered = true;
            if (this.userWordFilled.toLowerCase() === correctFilling.toLowerCase()) {
                this.correctInput = true;
                this.showTranslate = true;
                this.showTranslateFilled = true;
                this.userWordFilled = '';
            } else {
                this.translationFilled = this.compareAndHighlightWords(correctFilling, this.userWordFilled)
                this.showTranslate = true;
                this.showTranslateFilled = true;
                this.userWordFilled = '';
            }
        },
        wordsForCongratulationsAndFailures(type) {
            const randomIndex = Math.floor(Math.random() * type.length);

            return type[randomIndex];
        },
        compareAndHighlightWords(word1, word2) {
            if (word2.length === 0) {
                return 'You didn\'t answer';
            }

            if (word1.length !== word2.length) {
                return 'Wrong number of letters';
            }

            let output = '';
            for (let i = 0; i < word1.length; i++) {
                if (word1[i] !== word2[i]) {
                    output += '<span style="color: red;">' + word2[i] + '</span>';
                } else {
                    output += '<span style="color: green;">' + word1[i] + '</span>';
                }
            }

            return output;
        }
    }
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '@fortawesome/fontawesome-free/css/all.css';

defineProps(['visibleWords']);
</script>

<style scoped>

</style>
