<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Post;
use Illuminate\Http\Request;


Route::get('/', function () {

	$query = Post::orderBy('created_at', 'desc');

	if(!Auth::check()) {
		$query->where('is_private', false);
	}

	$posts = $query->get();

	return view('posts', [
		'posts' => $posts
	]);
});

Route::post('/post', function (Request $request) {
	$validator = Validator::make($request->all(), [
		'message' => 'required|max:255',
		'is_private' => 'nullable|numeric|min:0|max:1',
		'id' => 'nullable|numeric|min:0',
	]);
	if ($validator->fails()) {
		return redirect('/')
			->withInput()
			->withErrors($validator);
	}

	$id = (int) $request->id;
	if($id) {
		$post = Post::where('user_id', Auth::id())->findOrFail($id);
	} else {
		$post = new Post;
		$post->user_id = Auth::id();
	}

	$post->is_private = (int) $request->is_private;
	$post->message = $request->message;
	$post->save();

	return redirect('/');
})->middleware('auth');

Route::delete('/post/{id}', function ($id) {
	Post::where('user_id', Auth::id())->findOrFail($id)->delete();
	return redirect('/');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
