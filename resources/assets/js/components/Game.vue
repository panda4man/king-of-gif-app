<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    import SocketManager from '../socket-manager'
    import swal from 'sweetalert2'

    export default {
        name: 'game',
        socketManager: null,
        data() {
            return {
                gameId: null,
                joining: true,
                popup: null
            }
        },
        created() {
            this.socketManager = new SocketManager();
            this.gameId = this.$route.params.gameId;

            /* The host left the game */
            this.socketManager.socket.on('host-left', () => {
                swal('Host Left', 'Looks like the host abandoned you...', 'error');
                this.$router.replace({name: 'landing'});
            });

            /* Tell the server you joined the game */
            this.socketManager.socket.emit('game-joined', this.gameId);

            /* Server allowed joining of the game */
            this.socketManager.socket.on('game-joined-success', (game) => {
                this.$router.push({name: 'roundOne', params: {gameId: game.id}});
            });

            /* Tried to join nonexistent game */
            this.socketManager.socket.on('game-joined-404', () => {
                swal('Uh oh...', 'Could not find the game you were trying to join.', 'error');
                this.$router.replace('landing');
            });

            /* Some error happened when joining the game */
            this.socketManager.socket.on('game-joined-400', (res) => {
                if(res.constructor === Object && res['error']) {
                    swal('Uh oh', res.error, 'error');
                } else {
                    swal('Uh oh', 'There was an error joining the game. Sorry!', 'error');
                }

                this.$router.replace('landing');
            });
        }
    }
</script>