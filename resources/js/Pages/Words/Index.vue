<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import AddWord from '@/Components/AddWord.vue';

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
                        placeholder="The Article"
                        class="block w-1/4 mr-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    /><br>
                    <input
                        id="word-input"
                        type="text"
                        v-model="form.word"
                        placeholder="The Word"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                </div><br>
                <input
                    id="translation-input"
                    type="text"
                    v-model="form.translation"
                    placeholder="The Translation"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                />
                <InputError :message="form.errors.word" class="mt-2" />
                <div class="text-center">
                    <PrimaryButton class="mt-4">
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
