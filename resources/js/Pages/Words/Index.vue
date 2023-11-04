<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, useForm} from '@inertiajs/vue3';
import AddWord from '@/Pages/Words/AddWord.vue';

defineProps(['words']);

const form = useForm({
    article: '',
    word: '',
    translation: '',
});
</script>

<template>
    <Head title="Words" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('words.store'), { onSuccess: () => form.reset() })">
                <div class="flex flex-row">
                    <input
                        id="article-input"
                        type="text"
                        v-model="form.article"
                        @input="checkInputArticle()"
                        placeholder="Article"
                        maxlength="3"
                        class="block w-1/4 mr-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-center"
                    /><br>
                    <input
                        id="word-input"
                        type="text"
                        v-model="form.word"
                        @input="checkInputWord()"
                        @keyup.enter="keyupEnterWord()"
                        placeholder="Word"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                </div><br>
                <input
                    id="translation-input"
                    type="text"
                    v-model="form.translation"
                    @input="checkInputTranslation()"
                    @keyup.enter="keyupEnterTranslation()"
                    placeholder="Translation"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <span class="text-red-600">
                    <span v-if="articleError">{{ articleError }}<br></span>
                    <span v-if="wordError">{{ wordError }}<br></span>
                    <span v-if="translationError">{{ translationError }}</span>
                </span>
                <div class="text-center">
                    <PrimaryButton
                        v-if="isFormWordValid && isFormTranslationValid"
                        id="submit-button"
                        class="mt-4"
                        @click="saveForm"
                    >
                        {{ 'Add Word' }}
                    </PrimaryButton>
                    <button
                        v-else
                        disabled
                        class="inline-flex items-center mt-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150"
                    >
                        {{ 'Add Word' }}
                    </button>
                </div>
            </form>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <AddWord
                    v-for="word in words.slice(0, 3)"
                    :key="word.id"
                    :word="word"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "Index",
    data() {
        return {
            articleError: null,
            wordError: null,
            translationError: null,
            isFormWordValid: false,
            isFormTranslationValid: false
        }
    },
    methods: {
         checkInputArticle(save = false) {
            let input = document.getElementById("article-input");
            let value = input.value;

            if (value.length > 0) {
                input.value = value.charAt(0).toUpperCase() + value.slice(1);
            }

            if (save) {
                if (input.value.length !== 0 &&
                    input.value !== 'Der' &&
                    input.value !== 'Die' &&
                    input.value !== 'Das') {
                    this.articleError = 'Article must be "Der", "Die", or "Das", or empty..';
                }
            } else {
                if (input.value.length === 3 &&
                    (input.value === 'Der' || input.value === 'Die' || input.value === 'Das')) {
                    let nextInput = document.getElementById("word-input");

                    if (nextInput) {
                        nextInput.focus();
                    }
                } else {
                    this.articleError = null;
                }

                if (input.value.length === 3 && input.value !== 'Der' && input.value !== 'Die' && input.value !== 'Das') {
                    this.articleError = 'Article must be "Der", "Die", or "Das", or empty.';
                }
            }
        },
        keyupEnterWord() {
            document.getElementById("translation-input").focus();
        },
        checkInputWord() {
            let input = document.getElementById("word-input");
            let value = input.value;

            if (value.length === 0) {
                this.isFormWordValid = false;
                this.wordError = 'Field Word must not be empty.';
            } else {
                this.isFormWordValid = true;
                this.wordError = null;
            }
        },
        keyupEnterTranslation() {
            document.getElementById("submit-button").focus();
        },
        checkInputTranslation() {
            let input = document.getElementById("translation-input");
            let value = input.value;

            if (value.length === 0) {
                this.isFormTranslationValid = false;
                this.translationError = 'Field Translation must not be empty.';
            } else {
                this.isFormTranslationValid = true;
                this.translationError = null;
            }
        },
        saveForm() {
             this.checkInputArticle(true);
        }
    }
}
</script>
