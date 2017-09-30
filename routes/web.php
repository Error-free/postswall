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

	JavaScript::put([
		'user_id' => Auth::check() ? Auth::id() : 0,
	]);

	return view('posts');
});

Route::get('/posts', function () {

	$query = Post::orderBy('created_at', 'desc');

	if(!Auth::check()) {
		$query->where('is_private', false);
	}

	return $query->with('user')->get()->toJson();
});

Route::post('/send', function (Request $request) {
	$validator = Validator::make($request->all(), [
		'message' => 'required|max:255',
		'is_private' => 'boolean',
		'id' => 'nullable|numeric|min:0',
	]);
	if ($validator->fails()) {
		return response()->json(['errors' => $validator->errors()]);
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

	return 'success';
})->middleware('auth');

Route::delete('/post/{id}', function ($id) {
	Post::where('user_id', Auth::id())->findOrFail($id)->delete();
	return 'success';
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
