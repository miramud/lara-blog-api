<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use Illuminate\Http\Request;

class ForumCategoryController extends Controller
{
    // CREATE A CATEGORY
    public function createCategory(Request $request){
        $user = $request->user();

        if(!$user){
            return response(['message' => "unathorised access, please login"]);
        }
        //validate fields
        $attrs = $request->validate([
            'description' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'forums');

        $forum = ForumCategory::create([
            'body' => $attrs['body'],
            'description' => $attrs['description'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response([
            'message' => 'forum category created.',
            'post' => $forum,
        ], 200);
    }

    // FETCH ALL CATEGORIES
    public function getAllCategories(){
        $categories = ForumCategory::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('forums')->get();

        return response([
            'forumCategories' => $categories
        ], 200);
    }

    // FETCH A SINGLE CATEGORY
    public function getCategory($id){
        // $user = auth()->user();
        $category = ForumCategory::find($id);
        if(!$category){
            return response([
                "message" => "Forum not found"
            ], 403);
        }

        $forum = ForumCategory::where('id', $id)->withCount('forums')->get();

        return response([
            'forumCategory' => $category
        ], 200);

    }

    // FETCH ALL FORUMS UNDER A CATEGORY
    public function getAllForums($id) {
        $category = ForumCategory::find($id);

        if(!$category)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'forums' => $category->forums()->with('user:id,name,image')->get()
        ], 200);
    }

    // UPDATE CATEGORY
    public function updateCategory(Request $request, $id) {
        $category = ForumCategory::find($id);

        if(!$category) {
            return response([
                'message' => 'Forum not found.'
            ], 403);
        }

        if($category->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'description' => 'required|string'
        ]);
        $image = $this->saveImage($request->image, 'forums_categories');


        $category->update([
            'description' =>  $attrs['description'],
            'image' => $image
        ]);

        return response([
            'message' => 'Category updated.',
            // 'post' => $post
        ], 200);
    }

    // DELETE A CATEGORY
    public function deleteCategory($id){
        $forum = ForumCategory::find($id);

        if(!$forum)
        {
            return response([
                'message' => 'Forum category not found.'
            ], 403);
        }

        if($forum->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $forum->forums()->delete();
        $forum->delete();

        return response([
            'message' => 'Forum category deleted.'
        ], 200);
    }
}
