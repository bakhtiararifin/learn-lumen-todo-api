<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Post::where('user_id', '=', \Auth::user()->id)->get();
    }

    public function detail($id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        return $post;
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $post = Post::create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'user_id' => \Auth::user()->id,
        ]);

        return $post;
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->update($request->all());

        return response()->json([
            'message' => 'Post has been updated'
        ]);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post has been deleted'
        ]);
    }
}
