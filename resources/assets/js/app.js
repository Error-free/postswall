
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Echo from 'laravel-echo'

require('./bootstrap');


window.Vue = require('vue');

window.axios = require('axios');
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf-token').getAttribute('content');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));


Vue.component('post-item', {
	template: `	<div class="well">
					<h5>{{ post.username }}:</h5>

					{{ post.decodedMessage }}

					<div v-show="user_id == post.user_id">
						<button class="btn btn-danger" v-on:click="del(post, index)">
							Удалить
						</button>

						<button class="btn" v-on:click="edit(post)">
							Редактировать
						</button>
					</div>

					{{ post.created_at }}
				</div>`,
	props: ['post', 'index', 'user_id'],
	methods: {
		edit: function (post) {
			window.app.postForm = {
				id: post.id,
				message: post.decodedMessage,
				messageError: "",
				is_private: post.is_private
			};
		},
		del: function (post, index) {
			axios.delete('post/'+post.id)
				.then(function (response) {
					if(response.data == 'success') {
						window.app.postList.splice(index, 1);
					}
				})
				.catch(function (error) {
					console.log("post-item.del error", error);
				});
		}
	}
});



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