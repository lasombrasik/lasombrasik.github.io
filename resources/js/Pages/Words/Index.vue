<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head} from '@inertiajs/vue3';
import AddWord from '@/Pages/Words/AddWord.vue';

defineProps(['words']);
</script>

<template>
    <Head title="Words" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <div>
                <div class="flex flex-row">
                    <input
                        id="article-input"
                        type="text"
                        v-model="article"
                        @input="checkInputArticle()"
                        placeholder="Article"
                        maxlength="3"
                        class="block w-1/4 mr-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-center"
                    /><br>
                    <input
                        id="word-input"
                        type="text"
                        v-model="word"
                        @input="checkInputWord()"
                        @keyup.enter="keyupEnterWord()"
                        placeholder="Word"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                </div><br>
                <input
                    id="translation-input"
                    type="text"
                    v-model="translation"
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
                <span
                    v-if="addedSuccessfully"
                    class="text-green-600"
                >
                    {{ addedSuccessfully }}
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
            </div>
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
            article: null,
            word: null,
            translation: null,
            articleError: null,
            wordError: null,
            translationError: null,
            isFormWordValid: false,
            isFormTranslationValid: false,
            addedSuccessfully: null
        }
    },
    methods: {
         checkInputArticle(save = false) {
             if (this.article.length > 0) {
                 this.article = this.article.toLowerCase();
                 this.article = this.article.charAt(0).toUpperCase() + this.article.slice(1);
             }

            if (save) {
                if (this.article.length !== 0 &&
                    this.article !== 'Der' &&
                    this.article !== 'Die' &&
                    this.article !== 'Das') {
                    this.articleError = 'Article must be "Der", "Die", or "Das", or empty..';
                }
            } else {
                if (this.article.length === 3 &&
                    (this.article === 'Der' || this.article === 'Die' || this.article === 'Das')) {
                    let nextInput = document.getElementById("word-input");

                    if (nextInput) {
                        nextInput.focus();
                    }
                } else {
                    this.articleError = null;
                }

                if (this.article.length === 3 &&
                    this.article !== 'Der' &&
                    this.article !== 'Die' &&
                    this.article !== 'Das') {
                    this.articleError = 'Article must be "Der", "Die", or "Das", or empty.';
                }
            }
        },
        keyupEnterWord() {
            document.getElementById("translation-input").focus();
        },
        checkInputWord() {
            if (this.word.length === 0) {
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
            if (this.translation.length === 0) {
                this.isFormTranslationValid = false;
                this.translationError = 'Field Translation must not be empty.';
            } else {
                this.isFormTranslationValid = true;
                this.translationError = null;
            }
        },
        saveForm() {
             this.checkInputArticle(true);
             this.submitForm();
        },
        async submitForm() {
            try {
                const response = await axios.post(route('words.store'), {
                    article: this.article,
                    word: this.word,
                    translation: this.translation
                });

                this.articleError = '';
                this.wordError = '';
                this.translationError = '';
                this.addedSuccessfully = response.data.message;
                setTimeout(() => {
                    this.addedSuccessfully = null;
                }, 500);

                location.reload();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    this.articleError = errors.article && errors.article[0] ? errors.article[0] : null;
                    this.wordError = errors.word && errors.word[0] ? errors.word[0] : null;
                    this.translationError = errors.translation && errors.translation[0] ? errors.translation[0] : null;

                    setTimeout(() => {
                        this.articleError = null;
                        this.wordError = null;
                        this.translationError = null;
                    }, 1000);

                    if (this.wordError === 'This word has already been added') {
                        this.article = null;
                        this.word = null;
                        this.translation = null;
                    }
                }
            }
        },
    }
}
</script>
