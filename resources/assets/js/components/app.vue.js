import Echo from 'laravel-echo'

window.app = new Vue({
	el: '#app',
	data: {
		postList: [],
		postForm: {
			id: 0,
			message: "",
			messageError: "",
			is_private: false
		},
		user_id: 0
	},
	methods: {
		loadPostList: function () {
			axios.get('posts')
				.then(function (response) {
					window.app.postList = response.data;
				})
				.catch(function (error) {
					console.log("app.loadPostList error", error);
				});
		},
		sendPost: function () {
			var app = this;
			axios.post('send', this.postForm)
				.then(function (response) {
					if(response.data == 'success') {
						app.clearPostData();
						app.loadPostList();
					} else {
						if(response.data.hasOwnProperty('errors') && response.data.errors.hasOwnProperty('message')) {
							app.postForm.messageError = response.data.errors.message[0];
						}
					}
				})
				.catch(function (error) {
					console.log("app.sendPost.error", error);
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
		subscribe: function () {
			window.Echo = new Echo({
			    broadcaster: 'socket.io',
			    host: window.location.hostname + ':6001'
			});

			if(this.user_id != 0) {
				window.Echo.private('posts-wall')
				    .listen('PrivateWallUpdated', (e) => {
				        window.app.loadPostList();
				    });
			} else {
				window.Echo.channel('posts-wall')
				    .listen('WallUpdated', (e) => {
				        window.app.loadPostList();
				    });
			}
		}
	},
	mounted: function() {
		this.user_id = window.user_id;
		this.loadPostList();
		this.subscribe();
	}
});