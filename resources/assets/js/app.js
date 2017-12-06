import Vue from 'vue'
import VeeValidate from 'vee-validate'
import Axios from 'axios'
import VueInfiniteScroll from 'vue-infinite-scroll'
import io from 'socket.io-client'
import App from './components/App.vue'
import router from './router'
import SocketManager from './socket-manager'
import './route-listener'

//Install plugins
Vue.use(VeeValidate);
Vue.use(VueInfiniteScroll);

//Axios
Vue.prototype.$http = Axios;
Vue.prototype.$http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App },
    created() {
        let socketManager = new SocketManager;
        socketManager.connect(io);
    }
});

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Vue.prototype.$http.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
