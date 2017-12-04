import Vue from 'vue'
import Router from 'vue-router'
import Landing from './components/Landing.vue'
import Lobby from './components/Lobby.vue'

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '/',
            name: 'landing',
            component: Landing
        },
        {
            path: '/lobby/:roomCode',
            name: 'lobby',
            component: Lobby
        }
    ]
});
