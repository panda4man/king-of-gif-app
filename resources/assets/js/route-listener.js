import Router from './router'
import SocketManager from './socket-manager'

Router.beforeEach((to, from, next) => {
    let manager = new SocketManager;
    console.log(to, from);

    if(to.name === 'landing' && from.name === 'lobby') {
        manager.socket.emit('room-left', from.params.roomCode);
    }

    next();
});

