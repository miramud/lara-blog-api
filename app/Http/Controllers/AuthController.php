<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;

class AuthController extends Controller
{
    //REGISTER USER
    public function register(Request $request){
        // vallidate fields
        $attr = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed'
        ]);

        // create user
        $user = User::create([
            'name' => $attr['name'],
            'email' => $attr['email'],
            'username' => $attr['username'],
            'password' => bcrypt($attr['password'])
        ]);

        // return user and token
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    // LOGIN USER
    public function login(Request $request) {

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password]))
        {

            $user = Auth::user();
            $success['token'] =  $request->user()->createToken('secret')->plainTextToken;

            // return response()->json(['success' => $success], $this->successStatus);
            // return user and token
            return response([
                'user' => auth()->user(),
                'token' => $request->user()->createToken('secret')->plainTextToken
            ]);

        }
        // return response()->json(['error'=>'Unauthorised'], 401);
        return response(['messsage'=> 'Invalid credentials'], 403);
    }

    // LOGOUT USER
    public function logout(Request $request, User $user){
        $request->user()->tokens()->delete();
        return response(['message' => 'Logged out successfully'], 200);
    }

    // GET USER DETAILS
    public function user(){
        return response([
            'user' => auth()->user()
        ], 200);
    }

    // update user
    public function update(Request $request) {
        $user = User::find(auth()->user()->id);

        $attrs = $request->validate([
            'name' => auth()->user()->name,
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        $user->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User updated.',
            'user' => auth()->user()
        ], 200);
    }

    //delete post
    public function deleteUser($id)
    {
        $user = User::find($id);

        if(!$user)
        {
            return response([
                'message' => 'User not found.'
            ], 403);
        }

        if($user->id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $user->comment()->delete();
        // // $user->forums()->delete();
        $user->post()->delete();
        // $user->tokens()->delete();
        // $user->delete();

        return response([
            'message' => 'User deleted.'
        ], 200);
    }

}
