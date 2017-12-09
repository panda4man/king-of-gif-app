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

            }
        },
        created() {
            this.socketManager = new SocketManager();

            /* The host left the game */
            this.socketManager.socket.on('host-left', () => {
                swal('Host Left', 'Looks like the host abandoned you...', 'error');
                this.$router.replace({name: 'landing'});
            });
        }
    }
</script>