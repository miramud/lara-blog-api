<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ForumCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// public routes for users
Route::post('/register', [AuthController::class, 'register']); // register user
Route::post('/login', [AuthController::class, 'login']); //login user

// public routes for forums and categories
Route::get('/forum', [ForumController::class, 'displayAllForums']); // get all forums
Route::get('/forumcategory', [ForumCategoryController::class, 'displayAllCategories']); // get all forum categories
Route::get('/forum/{id}', [PostController::class, 'displayForum']); // get single forum
Route::get('/forumcategory/{id}', [PostController::class, 'displayCategory']); // get single forum category


// protected routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    // user
    Route::get('/user', [AuthController::class, 'user']); //get user details
    Route::post('/logout', [AuthController::class, 'logout']); // logout user
    // Route::get('/user/{id}', [AuthController::class, 'userDetails']); //get single user
    Route::put('/user/{id}', [AuthController::class, 'updateUser']); // update user
    Route::delete('/user/{id}', [AuthController::class, 'deleteUser']); // delete user

    // POSTS
    Route::get('/post', [PostController::class, 'displayAllPosts']); // get all posts
    Route::post('/post', [PostController::class, 'createPost']); // create new post
    Route::get('/post/{id}', [PostController::class, 'displayPost']); // get single posts
    Route::put('/post/{id}', [PostController::class, 'updatePost']); // update single post
    Route::delete('/post/{id}', [PostController::class, 'deletePost']); // delete a post

    // PROTECTED ROUTES FOR FORUMS AND CATEGORIES
    Route::post('/forum', [ForumController::class, 'createForum']); // create new forum
    Route::get('/forum', [ForumController::class, 'getAllForums']); // get all forums
    Route::get('/forum/{id}', [ForumController::class, 'getForum']); // get a single forum
    Route::get('/forum/{id}/posts', [ForumController::class, 'getForumPosts']); // get all posts from a single forum
    Route::put('/forum/{id}', [ForumController::class, 'updateForum']); //update single forum
    Route::delete('/forum/{id}', [ForumController::class, 'deleteForum']); // delete a forum

    Route::post('/forumcategory', [ForumCategoryController::class, 'createCategory']); // create new forum category
    Route::get('/forumcategory', [ForumCategoryController::class, 'getAllCategories']); // get all categories
    Route::get('/forumcategory/{id}', [ForumCategoryController::class, 'getCategory']); // get a single forum categories
    Route::get('/forumcategory/{id}/forums', [ForumCategoryController::class, 'getAllForums']); // get all forums from a category
    Route::put('/forumcategory/{id}', [ForumCategoryController::class, 'updateCategory']); // update single forum category
    Route::delete('/forumcategory/{id}', [ForumCategoryController::class, 'deleteCategory']); // delete a forum category

    // COMMENTS
    Route::post('/post/{id}/comment', [CommentController::class, 'createComment']); // create a comment on a post
    Route::get('/post/{id}/comments', [CommentController::class, 'displayComments']); // get all comments of a post
    Route::put('/comment/{id}', [CommentController::class, 'updateComment']); // update comment
    Route::delete('/comment/{id}', [CommentController::class, 'deleteComment']); // delete comment

    // LIKES
    Route::post('/post/{id}/like', [LikeController::class, 'likeOrDislike']); // create a comment on a post


});
