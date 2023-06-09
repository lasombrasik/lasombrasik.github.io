<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
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
                        @keyup.enter="checkInputWord()"
                        placeholder="Word"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                </div><br>
                <input
                    id="translation-input"
                    type="text"
                    v-model="form.translation"
                    @keyup.enter="checkInputTranslation()"
                    placeholder="Translation"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <InputError
                    :message="form.errors.word"
                    class="mt-2"
                />
                <div class="text-center">
                    <PrimaryButton
                        id="submit-button"
                        class="mt-4"
                    >
                        {{ 'Add Word' }}
                    </PrimaryButton>
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
    methods: {
         checkInputArticle() {
            let input = document.getElementById("article-input");
            let value = input.value;

            if (value.length > 0) {
                input.value = value.charAt(0).toUpperCase() + value.slice(1);
            }

            if (input.value.length === 3) {
                let nextInput = document.getElementById("word-input");

                if (nextInput) {
                    nextInput.focus();
                }
            }
        },
        checkInputWord() {
            document.getElementById("translation-input").focus();
        },
        checkInputTranslation() {
            document.getElementById("submit-button").focus();
        }
    }
}
</script>
