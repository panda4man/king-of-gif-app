<template>
    <div>
        <div class="columns">
            <div class="column is-hidden-touch is-half-tablet is-two-thirds-desktop has-text-centered">
                <img :src="selectedGif.url" v-if="selectedGif">
                <img src="https://placehold.it/400x400" v-else>
            </div>
            <div class="column">
                <button @click="showSearchModal" class="button is-primary is-fullwidth">
                    <span class="icon">
                        <i class="fa fa-search"></i>
                    </span>
                    <span>Search</span>
                </button>
            </div>
            <div class="column is-hidden-tablet is-half-tablet is-two-thirds-desktop has-text-centered">
                <img :src="selectedGif.url" v-if="selectedGif">
                <img src="https://placehold.it/400x400" v-else>
            </div>
        </div>
        <div class="modal" :class="{'is-active': modal.active}">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <form @submit.prevent="formSubmitHandler">
                        <div class="field">
                            <div class="control has-icons-left has-icons-right">
                                <input class="input" v-model.lazy="forms.searchGifs.q" v-debounce="600" type="text" placeholder="Cats...">
                                <span class="icon is-left">
                                    <i class="fa fa-search"></i>
                                </span>
                                <span class="icon is-right">
                                    <i class="fa fa-spin fa-spinner" v-if="http.searching"></i>
                                </span>
                            </div>
                        </div>
                    </form>
                </header>
                <section class="modal-card-body">
                    <br>
                    <div
                        v-infinite-scroll="nextPage"
                        infinite-scroll-disabled="scrollDisabled"
                        infinite-scroll-distance="30">
                        <div class="columns is-multiline gif-results">
                            <div class="column is-one-third gif-item" v-for="gif in gifResults">
                                <div @click="selectGif(gif)" class="has-text-centered wrapper" :class="{'selected': isSelectedGif(gif)}">
                                    <div class="overlay">
                                        <i class="fa fa-check"></i>
                                    </div>
                                    <img :src="gif.url">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button type="button" class="button" @click="hideSearchModal(true)">Cancel</button>
                    <button type="submit" class="button is-success" @click="hideSearchModal(false)">Use</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import GiphyService from '../../services/giphy-service'
    import swal from 'sweetalert2'
    import debounce from 'v-debounce'

    export default {
        directives: {debounce},
        data() {
            return {
                http: {
                    searching: false
                },
                modal: {
                    active: false
                },
                selectedGif: null,
                gifResults: [],
                lastResultCount: null,
                forms: {
                    searchGifs: {
                        q: '',
                        limit: 15,
                        offset: 0,
                        page: 0,
                        rating: 'pg'
                    }
                }
            }
        },
        watch: {
            'forms.searchGifs.q'(val) {
                //make sure for each query update, the pagination is set to page 1
                this.forms.searchGifs.offset = 0;
                this.forms.searchGifs.page = 0;
                this.searchGifs(true);

                if(!val) {
                    this.lastResultCount = null;
                }
            }
        },
        methods: {
            searchGifs(clearResults = false) {
                if(!this.forms.searchGifs.q)
                    return false;

                this.http.searching = true;

                if(clearResults) {
                    this.forms.searchGifs.offset = 0;
                    this.forms.searchGifs.page = 0;
                    this.gifResults = [];
                }

                GiphyService.search(
                    this.forms.searchGifs.q,
                    this.forms.searchGifs.rating,
                    this.forms.searchGifs.limit,
                    this.forms.searchGifs.offset
                ).then(res => {
                    let data = res.data.data;
                    this.lastResultCount = data.length;

                    this.http.searching = false;
                    this.gifResults.push.apply(this.gifResults, data);
                }).catch(res => {
                    this.http.searching = false;
                    swal('Uh oh...', 'Could not perform your search!', 'error');
                });
            },
            nextPage() {
                this.forms.searchGifs.page += 1;
                this.forms.searchGifs.offset = (this.forms.searchGifs.page) * this.forms.searchGifs.limit;

                if(this.lastResultCount === null || this.lastResultCount > 0) {
                    console.log('run pagination search');
                    this.searchGifs();
                }
            },
            showSearchModal() {
                this.modal.active = true;
            },
            hideSearchModal(clearGif = false) {
                this.modal.active = false;

                if(clearGif)
                    this.selectedGif = null;

                console.log(this.selectedGif);
            },
            selectGif(gif) {
                this.selectedGif = gif;
            },
            isSelectedGif(gif) {
                return this.selectedGif && gif.id === this.selectedGif.id;
            },
            formSubmitHandler() {
                return false;
            }
        },
        computed: {
            scrollDisabled() {
                return this.http.searching || !this.gifResults || this.gifResults.length < 1;
            }
        }
    }
</script>

<style>
    header, .modal-card-foot {
        padding: 10px !important;
    }
</style>
