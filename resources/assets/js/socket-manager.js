let instance = null;

export default class SocketManager {
    constructor() {
        if(!instance) {
            instance = this;
        }

        return instance;
    }

    connect(io) {
        this.io = io;
        this.socket = this.io(`${window.Laravel.socketUrl}:${window.Laravel.socketPort}`);
    }
}