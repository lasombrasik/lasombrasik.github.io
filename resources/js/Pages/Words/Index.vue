<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import AddWord from '@/Components/AddWord.vue';

defineProps(['words']);

const form = useForm({
    word: '',
    translation: '',
});
</script>

<template>
    <Head title="Words" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('words.store'), { onSuccess: () => form.reset() })">
                <input
                    type="text"
                    v-model="form.word"
                    placeholder="Enter a word"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                /><br>
                <input
                    type="text"
                    v-model="form.translation"
                    placeholder="Enter a translation"
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
