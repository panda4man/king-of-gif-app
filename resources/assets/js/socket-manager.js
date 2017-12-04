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
        let url = `${window.Laravel.socketUrl}`;
        
        if(window.Laravel.socketPort) {
            url += `:${window.Laravel.socketPort}`;
        }

        this.socket = this.io(url);
    }
}