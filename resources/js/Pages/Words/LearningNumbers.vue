<template>
    <Head title="Game: Generate Random Number" />

    <AuthenticatedLayout>
        <div class="mt-16 h-1/4 mx-auto sm:w-1/3 relative">
            <div
                v-if="!randomNumber"
                class="flex justify-center space-x-4 mb-4"
            >
                <input
                    id="input-number-from"
                    v-model="numberFrom"
                    class="border w-28 border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    type="number"
                    placeholder="From"
                    @keyup.enter="submit('input-check-number')"
                />
                <input
                    id="input-number-to"
                    v-model="numberTo"
                    class="border w-28 border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    type="number"
                    placeholder="To"
                />
            </div>
            <div
                v-if="!randomNumber"
                class="flex justify-center"
            >
                <button
                    id="button-generate-random-number"
                    class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                    @click="generateRandomNumber"
                >
                    {{ 'Generate Number' }}
                </button>
            </div>
            <div
                v-if="randomNumber !== null && !hiddenNumber"
                class="mt-16 text-center text-7xl text-green-700"
            >
               {{ randomNumber }}
            </div>
            <div
                v-if="randomNumber && !correctAnswer"
                class="text-3xl font-bold p-4 text-center"
            >
                <button
                    class="transform -translate-y-1/2 text-blue-400 hover:text-blue-600 font-bold rounded"
                    @click="speak(randomNumber)"
                    title="Listen to the word."
                >
                    <i class="fa fa-play-circle text-5xl"></i>
                </button>
            </div>
            <p
                v-if="randomNumber !== null && !hiddenNumber"
                class="success-text text-center"
            >
                {{ 'Correct answer!' }}
            </p>
            <p
                v-else-if="showFailureResult && hiddenNumber"
                class="failure-text text-center"
            >
                {{ 'Incorrect answer. Try again.' }}
            </p>
            <div
                v-if="randomNumber && hiddenNumber"
                class="flex justify-center space-x-4 mb-4"
            >
                <input
                    v-model="guessedNumber"
                    id="input-guessed-number"
                    type="number"
                    @keyup.enter="submit('input-check-number')"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter the Number"
                />
            </div>
            <div
                v-if="randomNumber && hiddenNumber"
                class="flex justify-center"
            >
                <button
                    id="input-check-number"
                    class="w-120 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    @click="checkNumber()"
                >
                    {{ 'Check Number' }}
                </button>
            </div>
            <div
                v-if="randomNumber && hiddenNumber"
                class="flex justify-center"
            >
                <button
                    class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-16"
                    @click="nextNumber()"
                >
                    {{ 'Regenerate' }}
                </button>

            </div>
            <div
                v-if="randomNumber && !hiddenNumber"
                class="flex justify-center mt-4"
            >
                <button
                    id="button-next-number"
                    class="w-120 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                    @click="nextNumber()"
                >
                    {{ 'Next Number' }}
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "LearningNumbers",
    data() {
        return {
            numberFrom: null,
            numberTo: null,
            randomNumber: null,
            hiddenNumber: true,
            guessedNumber: null,
            showFailureResult: false,
            correctAnswer: false
        };
    },
    methods: {
        generateRandomNumber() {
            const min = parseInt(this.numberFrom);
            const max = parseInt(this.numberTo);
            this.randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
            this.speak(this.randomNumber);

            setTimeout(() => {
                document.getElementById('input-guessed-number').focus();
            }, 100);
        },
        checkNumber () {
            this.correctAnswer = false;
            if (this.guessedNumber === this.randomNumber) {
                this.correctAnswer = true;
                this.hiddenNumber = false;

                setTimeout(() => {
                    document.getElementById('button-next-number').focus();
                }, 100);
            } else {
                this.showFailureResult = true;
                this.guessedNumber = null;

                document.getElementById('input-guessed-number').focus();
            }

            setTimeout(() => {
                this.showFailureResult = false;
            }, 1500);
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
        nextNumber() {
            this.randomNumber = null;
            this.hiddenNumber = true;
            this.correctAnswer = false;
            this.guessedNumber = null;

            setTimeout(() => {
                document.getElementById('button-generate-random-number').focus();
            }, 100);
        },
        submit() {
            document.getElementById('input-check-number').click();
        }
    },
}
</script>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import '@fortawesome/fontawesome-free/css/all.css';

</script>

<style scoped>
@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

.success-text,
.failure-text {
    animation-name: fadeOut;
    animation-duration: 3s;
    animation-fill-mode: forwards;
}

.success-text {
    color: green;
}

.failure-text {
    color: red;
}
</style>
