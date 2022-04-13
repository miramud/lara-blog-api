<?php

namespace App\Http\Controllers;
use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    // CREATE FORUM
    public function createForum(Request $request){
        $user = $request->user();

        if(!$user){
            return response(['message' => "unathorised access, please login"]);
        }
        //validate fields
        $attrs = $request->validate([
            'description' => 'required|string',
            'summary' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'forums');

        $forum = Forum::create([
            'body' => $attrs['body'],
            'description' => $attrs['description'],
            'summary' => $attrs['summary'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response([
            'message' => 'forum created.',
            'post' => $forum,
        ], 200);
    }

    // GET ALL FORUMS
    public function displayAllForums() {
        $post = Forum::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('posts')->get();

        return response([
            'forum' => $post
        ], 200);
    }


    // GET A SINGLE FORUM
    public function getForum($id){
        // $user = auth()->user();
        $forum = Forum::find($id);
        if(!$forum){
            return response([
                "message" => "Forum not found"
            ], 403);
        }

        $forum = Forum::where('id', $id)->withCount('posts', 'likes')->get();

        return response([
            'forum' => $forum
        ], 200);

    }

    // GET ALL POSTS FROM A SINGLE FORUM
    public function getForumPosts($id) {
        $forum = Forum::find($id);

        if(!$forum)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'posts' => $forum->posts()->with('user:id,name,image')->get()
        ], 200);
    }

    // UPDATE FORUM
    public function updateForum(Request $request, $id) {
        $forum = Forum::find($id);

        if(!$forum) {
            return response([
                'message' => 'Forum not found.'
            ], 403);
        }

        if($forum->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'description' => 'required|string',
            'summary' => 'required|string'
        ]);
        $image = $this->saveImage($request->image, 'forums');


        $forum->update([
            'description' =>  $attrs['description'],
            'summary' =>  $attrs['summary'],
            'image' => $image
        ]);

        return response([
            'message' => 'Forum updated.',
            // 'forum' => $forum
        ], 200);
    }


    // DELETE FORUM
    public function deleteForum($id){
        $forum = Forum::find($id);

        if(!$forum)
        {
            return response([
                'message' => 'Forum not found.'
            ], 403);
        }

        if($forum->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $forum->posts()->delete();
        $forum->delete();

        return response([
            'message' => 'Forum deleted.'
        ], 200);
    }
}
