<template>
	<div class="columns">
		<div class="column">
			<div class="card">
				<div class="card-image" v-if="randomGif">
					<figure class="image">
						<img :src="randomGif">
					</figure>
				</div>
				<div class="card-content">
					<div class="content">
						Funniest person wins, duh.
					</div>
				</div>
			</div>
		</div>
		<div class="column is-one-third">
			<div class="card">
				<header class="card-header">
					<p class="card-header-title">
						Room Code: {{roomCode}}
					</p>
				</header>
				<div class="card-content">
					<div class="content">
						<ul class="player-list">
							<li v-for="player in gamePlayers" class="player">
								{{player.username}}
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
    import GiphyService from '../services/giphy-service'
	import SocketManager from '../socket-manager'
	import swal from 'sweetalert2'

	export default {
		name: 'Lobby',
		socketManager: null,
		data() {
			return {
			    randomGif: null,
			    roomCode: null,
				players: [],
				interval: null
			}
		},
		created() {
		    this.socketManager = new SocketManager;
		    this.roomCode = this.$route.params.roomCode;

			/* Tell the server you are on the lobby page */
			this.socketManager.socket.emit('lobby-joined');

			/* Confirmation when someone joins the lobby from the server */
			this.socketManager.socket.on('lobby-joined-confirmed', (player) => {
				this.players.push(player);
			});

			/* A new player joined the lobby */
			this.socketManager.socket.on('player-joined', (player) => {
				this.players.push(player)
			});

			/* A player left the lobby */
			this.socketManager.socket.on('player-left', (player) => {
				this.players = this.players.filter((p) => {
					return p.id !== player.id
				});
			});

			/* Host left so get out */
			this.socketManager.socket.on('host-left', () => {
			    swal('Host Left', 'Looks like the host abandoned you...', 'error');
			   	this.$router.replace({name: 'landing'});
			});
		},
        mounted() {
            this.getRandomGif();
            this.interval = setInterval(this.getRandomGif, 4000);
        },
        beforeDestroy() {
            clearInterval(this.interval);
        },
		methods: {
            getRandomGif() {
                GiphyService.random('').then(res => {
                    let data = res.data.data;
                    this.randomGif = data.url;
                }).catch(res => {
                    if(res.response) {

                    }
                });
            }
		},
		computed: {
			gamePlayers() {
				return this.players.filter(function (p) {
					return !p.is_host;
				});
			},
			me() {
			  	let p = this.players.filter(p => {
			  	    return p.
				});
			}
		}
	}
</script>