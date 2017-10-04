<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use \Validator;
use \JavaScript;
use \Auth;

class PostController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'posts']);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        JavaScript::put([
            'user_id' => Auth::check() ? Auth::id() : 0,
        ]);
        return view('posts');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function posts()
    {
        $query = Post::orderBy('created_at', 'desc')->where('is_private', false);

        if(Auth::check()) {
            $query->orWhere([
                ['is_private', '=', true],
                ['user_id', '=', Auth::id()]
            ]);
        }

        return $query->with('user')->get()->toJson();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
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

        $old_is_private = $post->is_private;

        $post->is_private = (int) $request->is_private;
        $post->message = $request->message;
        $post->save();

        if(!$post->is_private || ($old_is_private === 0)) {
            event(new \App\Events\WallUpdated());
        }

        return 'success';
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        $is_private = $post->is_private;

        $post->delete();

        if(!$is_private) {
            event(new \App\Events\WallUpdated());
        }

        return 'success';
    }
}
