<template>
	<div class="columns" v-if="me">
		<template v-if="me.is_host">
			{{me}}
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
		</template>
		<template v-else>
			<div class="column">
				<article class="message is-primary">
					<div class="message-header">
						<p>Waiting for Players</p>
					</div>
					<div class="message-body">
						<p>Please enjoy these random GIFs while you wait!</p>
						<button v-if="isFirstPlayer" class="button is-info is-fullwidth start-game" @click="startGame">Start</button>
						<div class="has-text-centered">
							<img :src="randomGif" class="player-random-gif" v-if="randomGif">
						</div>
					</div>
				</article>
			</div>
		</template>
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
			this.socketManager.socket.emit('room-joined', this.roomCode);

			/* Confirmation when someone joins the lobby from the server */
			this.socketManager.socket.on('room-joined-confirmed', (players) => {
			    this.players = [];
				this.players = players;
			});

			/* Edge case where upon joining the player isn't found in the player list on the server */
			this.socketManager.socket.on('room-joined-404', () => {
			   	swal('Uh oh...', 'We could not find you in the player list.', 'error');
                this.$router.replace({name: 'landing'});
			});

			/* A new player joined the lobby */
			this.socketManager.socket.on('player-joined', (players) => {
			    this.players = [];
				this.players = players || [];
			});

			/* A player left the lobby */
			this.socketManager.socket.on('player-left', (players) => {
			    this.players = [];
				this.players = players || [];
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
            },
			startGame() {
				this.socketManager.emit('game-start');
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
			  	    return p.socket_id = this.socketManager.socket.id;
				});

			  	if(p && p.length) {
			  	    return p[0];
				}

				return null;
			},
			isFirstPlayer() {
			    let isFirst = false;

			    if(this.players.length) {
			        if(this.players[0].socket_id = this.socketManager.socket.id) {
			            isFirst = true;
					}
				}

			    return isFirst;
			}
		}
	}
</script>

<style>
	.player-random-gif {
		margin-top: 20px;
	}

	.start-game {
		margin-top: 10px;
	}
</style>