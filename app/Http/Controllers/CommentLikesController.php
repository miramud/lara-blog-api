<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class CommentLikesController extends Controller
{
    // like or unlike
    public function likeorunlike($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::createLike([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ], 200);
        }
        // else dislike it
        $like->deleteLike();

        return response([
            'message' => 'Disliked'
        ], 200);
    }

}
