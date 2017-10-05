import Echo from 'laravel-echo'

window.app = new Vue({
	el: '#app',
	data: {
		cypher: {
			password: ''
		},
		postList: [],
		postForm: {
			id: 0,
			message: '',
			messageError: '',
			is_private: false
		},
		passwordInput: '',
		user_id: 0,
		showModal: false
	},
	computed: {
		filteredPosts: function () {
			return this.postList.filter(function (el) {
				return !el.is_private || window.app.cypher.password;
			})
		}
	},
	methods: {
		loadPostList: function () {
			axios.get('posts')
				.then(function (response) {
					window.app.postList = response.data;
					window.app.decryptPosts();
				})
		},
		sendPost: function () {
			var data = {
				id: this.postForm.id,
				message: this.postForm.message,
				is_private: this.postForm.is_private
			};

			if(data.is_private && this.cypher.password) {
				data.message = window.cryptojs.AES.encrypt(data.message, this.cypher.password).toString();
			}

			var app = this;
			axios.post('send', data)
				.then(function (response) {
					if(response.data == 'success') {
						app.clearPostData();
						app.loadPostList();
					} else {
						if(response.data.hasOwnProperty('errors') && response.data.errors.hasOwnProperty('message')) {
							app.postForm.messageError = response.data.errors.message[0];
						}
					}
				});
		},
		clearPostData: function () {
			this.postForm = {
				id: 0,
				message: "",
				messageError: "",
				is_private: false
			};
		},
		decryptPosts: function () {
			window.app.postList.forEach(function(el, k) {
				var bytes;
				if(window.app.postList[k].is_private) {
					if(window.app.cypher.password) {
						bytes  = window.cryptojs.AES.decrypt(window.app.postList[k].message, window.app.cypher.password);
						window.app.postList[k].decryptedMessage = bytes.toString(window.cryptojs.enc.Utf8);
					}
				} else {
					window.app.postList[k].decryptedMessage = window.app.postList[k].message;
				}
			});
		},
		setPassword: function () {
			var oldPassword = this.cypher.password;

			this.cypher.password = this.passwordInput;

			if(oldPassword) {
				var app = window.app;
				this.postList.forEach(function(el, k) {
					if(app.postList[k].is_private && oldPassword != '') {
						axios.post('send', {
								id: app.postList[k].id,
								message: window.cryptojs.AES.encrypt(app.postList[k].decryptedMessage, app.cypher.password).toString(),
								is_private: app.postList[k].is_private
							});
					}
				});
			} else {
				this.decryptPosts();
			}
			localStorage.setItem('password', this.cypher.password);
			this.showModal = false;
		},
		subscribe: function () {
			window.Echo = new Echo({
			    broadcaster: 'socket.io',
			    host: window.location.hostname + ':6001'
			});

			window.Echo.channel('posts-wall')
			    .listen('WallUpdated', (e) => {
			        window.app.loadPostList();
			    });
		}
	},
	mounted: function() {
		this.cypher.password = localStorage.getItem('password');
		this.user_id = window.user_id;
		this.loadPostList();
		this.subscribe();
	}
});