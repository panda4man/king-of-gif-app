import Vue from 'vue'

export default class GiphyService {
    /**
     * Fetch a random gif from our api.
     *
     * @param tag
     * @param rating
     */
    static random(tag = '', rating = 'pg') {
        let url = '/giphy/random';

        return Vue.prototype.$http.get(url, {
            params: {
                tag,
                rating
            }
        });
    }

    /**
     * Run a search against the giphy api.
     *
     * @param q
     * @param rating
     * @param limit
     * @param offset
     */
    static search(q = '', rating = 'pg', limit = null,  offset = null) {
        let url = '/giphy/search';
        let params = {
            q,
            rating,
            limit,
            offset
        };

        return Vue.prototype.$http.get(url, {
            params: params
        });
    }
}