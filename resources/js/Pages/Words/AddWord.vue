<template>
    <div class="p-6 flex space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <small class="ml-2 text-sm text-gray-600">
                        {{ dayjs(word.created_at).fromNow() }}
                    </small>
                    <small
                        v-if="word.created_at !== word.updated_at"
                        class="text-sm text-gray-400"
                    > {{ '&middot; edited' }}
                    </small><br>
                    <span v-if="word.article">
                        <span class="text-black-400">
                            {{ 'Article:' }}
                        </span>
                        <span class="text-green-600">
                            {{ word.article }}
                        </span><br>
                    </span>
                    <span class="text-black-400">
                        {{ 'Word:' }}
                    </span>
                    <span class="text-green-600">
                        {{ word.word }}
                    </span><br>
                    <span class="text-black-400">
                        {{ 'Translition:' }}
                    </span>
                    <span class="text-green-600">
                        {{ word.translation }}
                    </span>
                    <br>
                    <span class="text-black-400">
                        {{ 'has been added!' }}
                    </span>
                </div>
                <Dropdown>
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>

                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out"
                            @click="editing = true"
                        >
                            {{ 'Edit' }}
                        </button>
                        <DropdownLink
                            as="button"
                            :href="route('words.destroy', word.id)"
                            method="delete"
                        >
                            {{ 'Delete' }}
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
            <template v-if="editing">
                <input
                    id="form-article-input"
                    type="text"
                    v-model="form.article"
                    @input="checkInputArticle(false)"
                    placeholder="Article"
                    maxlength="3"
                    class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <span v-if="form.error.article" class="text-red-600 mt-2">{{ form.error.article }}</span>
                <input
                    id="form-word-input"
                    type="text"
                    v-model="form.word"
                    @input="checkInputWord()"
                    placeholder="Word"
                    class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <span v-if="form.error.word" class="text-red-600 mt-2">{{ form.error.word }}</span>
                <input
                    id="form-translation-input"
                    type="text"
                    v-model="form.translation"
                    @input="checkInputTranslation()"
                    placeholder="Translation"
                    class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <span v-if="form.error.translation" class="text-red-600 mt-2">{{ form.error.translation }}</span>
                <div class="flex justify-center space-x-2">
                    <PrimaryButton
                        @click=actionSave(word.id)
                        class="mt-4">
                        {{ 'Save' }}
                    </PrimaryButton>
                    <button
                        class="mt-4"
                        @click="actionCancel">
                        {{ 'Cancel' }}
                    </button>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "AddWord",
    props: {
        word: Object,
    },
    data() {
        return {
            form: {
                article: this.word.article,
                word: this.word.word,
                translation: this.word.translation,
                error: {
                    article: null,
                    word: null,
                    translation: null
                }
            },
            editing: false
        }
    },
    methods: {
        checkInputArticle(save = false) {
            if (this.form.article.length > 0) {
                this.form.article = this.form.article.toLowerCase();
                this.form.article = this.form.article.charAt(0).toUpperCase() + this.form.article.slice(1);
            }

            if (save) {
                if (this.form.article.length !== 0 &&
                    this.form.article !== 'Der' &&
                        this.form.article !== 'Die' &&
                        this.form.article !== 'Das') {
                    this.form.error.article = 'Article must be "Der", "Die", or "Das", or empty..';
                } else {
                    this.form.error.article = null;
                }
            } else {
                if (this.form.article.length === 3 &&
                    (this.form.article === 'Der' || this.form.article === 'Die' || this.form.article === 'Das')) {
                    let nextInput = document.getElementById("form-word-input");
                    this.form.error.article = null;

                    if (nextInput) {
                        nextInput.focus();
                    }
                } else {
                    this.form.error.article = null;
                }

                if (this.form.article.length === 3 &&
                    this.form.article !== 'Der' &&
                    this.form.article !== 'Die' &&
                    this.form.article !== 'Das') {

                    this.form.error.article = 'Article must be "Der", "Die", or "Das", or empty..';
                }
            }
        },
        checkInputWord() {
            if (this.form.word.length === 0) {
                this.form.error.word = 'Field Word must not be empty.';
            }

            this.form.error.word = null;
        },
        checkInputTranslation() {
            if (this.form.translation.length === 0) {
                this.form.error.translation = 'Field Translation must not be empty.';
            }

            this.form.error.translation = null;
        },
        async actionSave(id) {
            this.checkInputArticle(true);
            this.checkInputWord();
            this.checkInputTranslation();

            if (this.form.error.article === null &&
                this.form.error.word === null &&
                this.form.error.translation === null) {
                await axios.put(`/words/${id}`, {
                    article: this.form.article,
                    word: this.form.word,
                    translation: this.form.translation
                })
                    .then(response => {
                        this.word.article = this.form.article;
                        this.word.word = this.form.word;
                        this.word.translation = this.form.translation;
                        this.editing = false;
                        console.log('The entry was successfully edited.');
                    })
                    .catch(error => {
                        console.error('Error while editing record', error);
                    });

            }
        },
        actionCancel() {
            this.editing = false;
            this.form.error.article = null;
            this.form.error.word = null;
            this.form.error.translation = null;
        }
    }
}
</script>

<script setup>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import Dropdown from '@/Components/Dropdown.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import DropdownLink from '@/Components/DropdownLink.vue';

dayjs.extend(relativeTime);
</script>


<style scoped>

</style>
