import Router from './router'
import SocketManager from './socket-manager'

Router.beforeEach((to, from, next) => {
    let manager = new SocketManager;

    //Going from lobby to landing means you get booted :)
    if(to.name === 'landing' && from.name === 'room') {
        manager.socket.emit('room-left', from.params.roomCode);
    }

    next();
});

