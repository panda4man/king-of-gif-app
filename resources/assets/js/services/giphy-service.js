import Vue from 'vue'

export default class GiphyService {
    static random(tag = '', rating = 'g') {
        let url = '/giphy/random';

        return Vue.prototype.$http.get(url, {
            params: {
                tag,
                rating
            }
        });
    }
}