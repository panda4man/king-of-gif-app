import Vue from 'vue'
import Router from 'vue-router'
import Landing from './components/Landing.vue'
import Room from './components/Room.vue'
import Game from './components/Game.vue'
import FirstRound from './components/game/FirstRound.vue'

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '/',
            name: 'landing',
            component: Landing
        },
        {
            path: '/room/:roomCode',
            name: 'room',
            component: Room
        },
        {
            path: '/game/:gameId',
            name: 'game',
            component: Game,
            children: [
                {
                    path: 'round-1',
                    name: 'roundOne',
                    component: FirstRound
                }
            ]
        }
    ]
});
