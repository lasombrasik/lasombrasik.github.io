<template>
    <AuthenticatedLayout>
        <div class="h-1/4 mx-auto sm:w-1/2 relative">
            <span v-text="words['word']"></span>
            <div class=" text-black-600 font-semibold sm:text-lg text-center">
                <span>{{ 'Total words:' }} {{ words.length }}</span>
            </div>
            <EasyDataTable
                buttons-pagination
                :headers="headers"
                :items="words"
                show-index
            >
                <template #item-created_at="item">
                    {{ formattedDate(item.created_at) }}
                </template>

                <template #item-operation="item">
                    <div class="operation-wrapper">
                        <button
                            class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 mr-3"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            @click="openModal(item)"
                        >
                            <span>{{ 'Edit' }}</span>
                        </button>

                        <div
                            class="modal fade"
                            id="modalEdit"
                            tabindex="-1"
                            aria-labelledby="exampleModalLabel"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5
                                            class="modal-title"
                                            id="exampleModalLabel"
                                        >
                                            {{ 'Word Editing' }}
                                        </h5>
                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                        ></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="mb-3">
                                                <label
                                                    for="article"
                                                    class="form-label"
                                                >{{ 'Article' }}</label>
                                                <input
                                                    v-model="selectedItemArticle"
                                                    @input="validateArticle(false)"
                                                    type="text"
                                                    class="form-control"
                                                    id="article"
                                                    maxlength="3"
                                                >
                                                <span
                                                    v-if="articleError"
                                                    class="text-danger"
                                                >
                                                    {{ articleError }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <label
                                                    for="word"
                                                    class="form-label"
                                                >{{ 'Word' }}</label>
                                                <input
                                                    v-model="selectedItemWord"
                                                    @input="validateWord"
                                                    type="text"
                                                    class="form-control"
                                                    id="word"
                                                >
                                                <span
                                                    v-if="wordError"
                                                    class="text-danger"
                                                >
                                                    {{ wordError }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <label
                                                    for="translation"
                                                    class="form-label"
                                                >{{ 'Translation' }}</label>
                                                <input
                                                    v-model="selectedItemTranslation"
                                                    @input="validateTranslation"
                                                    type="text"
                                                    class="form-control"
                                                    id="translation"
                                                >
                                                <span
                                                    v-if="translationError"
                                                    class="text-danger"
                                                >
                                                    {{ translationError }}
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >{{ 'Close' }}</button>
                                        <button
                                            @click="actionEdit(item)"
                                            type="button"
                                            class="btn btn-primary"
                                        >{{ 'Save' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button
                            class="bg-red-600 text-white px-2 py-1 rounded-md hover:bg-red-700"
                            @click="actionDelete(item.id)"
                        >
                            <span>{{ 'Delete' }}</span>
                        </button>
                    </div>
                </template>
            </EasyDataTable>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';


export default {
    name: 'AddedWords',
    props: {
        words: Array,
    },
    components:{
        AuthenticatedLayout,
        EasyDataTable
    },
    data() {
        return {
            headers: [
                { text: "Article", value: "article", width: 30 },
                { text: "Word", value: "word", sortable: true },
                { text: "Translation", value: "translation", sortable: true },
                { text: "Created", value: "created_at", sortable: true },
                { text: "Operation", value: "operation" }
            ],
            items: [],
            editing: false,
            selectedItemId: '',
            selectedItemWord: '',
            selectedItemArticle: '',
            selectedItemTranslation: '',
            articleError: null,
            wordError: null,
            translationError: null,
        }
    },
    methods: {
        formattedDate(date) {
            const dateObject = new Date(date);
            const year = dateObject.getFullYear();
            const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
            const day = dateObject.getDate().toString().padStart(2, '0');

            return `${day}.${month}.${year}`;
        },
        async actionDelete(id) {
            await axios.delete(`/words/${id}`)
                .then(response => {
                    window.location.reload()
                    console.log('The entry was successfully deleted.');
                })
                .catch(error => {
                    console.error('Error while deleting record', error);
                });
        },
        openModal(item) {
            if (item && item.word) {
                this.selectedItemId = item.id;
                this.selectedItemArticle = item.article ? item.article.charAt(0).toUpperCase() + item.article.slice(1) : '';
                this.selectedItemWord = item.word;
                this.selectedItemTranslation = item.translation;
            }
        },
        async actionEdit(item) {
            this.validateArticle(true);
            this.validateWord();
            this.validateTranslation();

            if (!this.articleError && !this.wordError && !this.translationError) {
                await axios.put(`/words/${item.id}`, {
                    edit: true,
                    article: this.selectedItemArticle,
                    word: this.selectedItemWord,
                    translation: this.selectedItemTranslation
                })
                    .then(response => {
                        window.location.reload()
                        console.log('The entry was successfully edited.');
                    })
                    .catch(error => {
                        console.error('Error while editing record', error);
                    });
            }
        },
        capitalizeFirstLetter() {
            let value = this.selectedItemArticle;
            if (value.length > 0) {
                this.selectedItemArticle = value.charAt(0).toUpperCase() + value.slice(1);
            }
        },
        validateArticle(edit = false) {
            this.capitalizeFirstLetter();

            if (this.selectedItemArticle.length === 3 || (edit && this.selectedItemArticle.length !== 0)) {
                if (!/^(Der|Die|Das)$/i.test(this.selectedItemArticle)) {
                    this.articleError = 'Invalid Article format. Valid values are Der, Die, Das.';
                } else {
                    this.articleError = null;
                }
            } else {
                this.articleError = null;
            }
        },
        validateTranslation() {
            if (this.selectedItemTranslation.length === 0) {
                this.translationError = 'This field must not be empty.';
            } else {
                this.translationError = null;
            }
        },
        validateWord() {
            if (this.selectedItemWord.length === 0) {
                this.wordError = 'This field must not be empty.';
            } else {
                this.wordError = null;
            }
        },
    },
}
</script>
