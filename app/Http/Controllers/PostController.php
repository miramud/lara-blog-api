<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    // get all posts
    public function displayAllPosts() {
        $post = Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->with('likes', function($like) {
            return $like->where('user_id', auth()->user()->id)->select('id', 'user_id', 'post_id')->get();
        })->get();

        return response([
            'posts' => $post
        ], 200);
    }

    // get a single post
    public function displayPost($id) {
        $post = Post::where('id', $id)->withCount('comments', 'likes')->get();

        return response([
            'posts' => $post
        ], 200);
    }

    // create a post
    public function createPost(Request $request) {
        $user = $request->user();

        if(!$user){
            return response(['message' => "unathorised, access denied, please login"]);
        }
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response([
            'message' => 'Post created.',
            'post' => $post,
        ], 200);
    }

    // update a post
    public function updatePost(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' =>  $attrs['body']
        ]);

        return response([
            'message' => 'Post updated.',
            // 'post' => $post
        ], 200);
    }

    //delete post
    public function deletePost($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.'
        ], 200);
    }
}
