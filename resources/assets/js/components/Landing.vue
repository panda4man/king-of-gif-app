<template>
	<div class="columns is-centered">
		<div class="column is-two-thirds-desktop">
			<div class="card">
				<div class="card-content">
					<div class="content">
						<div class="columns">
							<div class="column">
								<div class="has-text-centered">
									<img :src="randomGif" v-if="randomGif">
								</div>
							</div>
							<div class="column">
								<h3 class="has-text-centered">New Game</h3>
								<div class="has-text-centered">
									<button class="button is-success" @click="start">Get Started</button>
								</div>
								<hr>
								<h3 class="has-text-centered">Join Game</h3>
								<form @submit.prevent="joinRoomValidate">
									<div class="field">
										<div class="control">
											<input v-validate="'required'" data-vv-as="room code" :class="{'is-danger': errors.has('roomCode')}" name="roomCode" class="input" v-model="forms.join.roomCode" placeholder="Room Code">
										</div>
										<div class="helper is-danger" v-if="errors.has('roomCode')">
											<div v-for="error in errors.collect('roomCode')">{{error}}</div>
										</div>
									</div>
									<div class="field">
										<div class="control">
											<input v-validate="'required'" :class="{'is-danger': errors.has('username')}" name="username" class="input" v-model="forms.join.username" placeholder="Username">
										</div>
										<div class="helper is-danger" v-if="errors.has('username')">
											<div v-for="error in errors.collect('username')">{{error}}</div>
										</div>
									</div>
									<div class="field has-text-centered">
										<button class="button is-info" type="submit">Join</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
	import SocketManager from '../socket-manager'
	import swal from 'sweetalert2'
    import GiphyService from '../services/giphy-service'

	export default {
		name: 'Landing',
		socketManager: null,
		data() {
			return {
				forms: {
					join: {
						roomCode: '',
						username: ''
					}
				},
				interval: null,
				randomGif: null,
			}
		},
        mounted() {
		    this.getRandomGif();
            this.interval = setInterval(this.getRandomGif, 8000);
        },
        beforeDestroy() {
            clearInterval(this.interval);
        },
		created() {
		    this.socketManager = new SocketManager();

		    /* Successfully joined the room */
			this.socketManager.socket.on('room-joined', () => {
				this.$router.push({name: 'lobby', params: {roomCode: this.forms.join.roomCode}});
			});

			/* Successfully created the new game */
			this.socketManager.socket.on('game-created', (roomCode) => {
				this.$router.push({name: 'lobby', params: {roomCode}});
			});

			/* Joining room does not exist */
			this.socketManager.socket.on('room-404', () => {
			   	 swal('Uh oh!', `Could not find a room for ${this.forms.join.roomCode}`);
			});

			/* Can't join a room w/o a host */
			this.socketManager.socket.on('room-no-host', () => {
			    swal('Uh oh', `This room does not have a host!`);
			});

			/* Can't join a rull room */
			this.socketManager.socket.on('room-full', () => {
			   	swal('Uh oh', 'This room is full. Try another fool.');
			});
		},
		methods: {
			start() {
				this.socketManager.socket.emit('game-create');
			},
			joinRoom() {
				this.socketManager.socket.emit('room-join', this.forms.join);
			},
			joinRoomValidate() {
			    this.$validator.validateAll().then(res => {
			        if(res) {
			            this.joinRoom();
					}
				});
			},
            getRandomGif() {
                GiphyService.random('', 'pg').then(res => {
                    let data = res.data.data;
                    this.randomGif = data.url;
                }).catch(res => {
                    if(res.response) {

                    }
                });
            }
		}
	}
</script>