Vue.component('post-item', {
	template: `	<div class="well">
					<h5>{{ post.username }}:</h5>

					{{ post.message }}

					<div v-show="user_id == post.user_id">
						<button class="btn btn-danger" v-on:click="del(post, index)">
							Удалить
						</button>

						<button class="btn" v-on:click="edit(post)">
							Редактировать
						</button>
					</div>

					<div>
						{{ post.created_at }}
					</div>
				</div>`,
	props: ['post', 'index', 'user_id'],
	methods: {
		edit: function (post) {
			window.app.postForm = {
				id: post.id,
				message: post.message,
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