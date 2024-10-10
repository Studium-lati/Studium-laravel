<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users|max:10| min:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'string','default:user','in:admin,user,owner',
            'avatar' => 'string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $request->avatar
        ]);

        return response()->json(['user' => $user, 'message' => 'user registered successfully'], 201
    );

    }
    public Function login(Request $request){
        $request->validate([
            'phone'=>'required|string',
            'password'=>'required|string'
        ]);
        $credentials =request(['phone','password']);
        if (!$token=auth('api')->attempt($credentials)) {
            return response()->json([
                'error'=> 'Unauthorized'],401
                );
                
        }
             return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
        

    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['message'=>'User logged out successfully']);
    }
    public function profile(){
        return response()->json(auth('api')->user());
    }
   
    public function update(Request $request){
        $request->validate([
            'name' => 'string',
            'phone' => 'string|max:10| min:10',
            'email' => 'email',
            'password' => 'string',
            'role' => 'string|in:admin,user,owner',
            'avatar' => 'string'
        ]);
        $user = auth('api')->user();
        $user->update($request->all());
        return response()->json(['user' => $user, 'message' => 'user updated successfully'], 200);
    }

    public function destroy(){
        $user = auth('api')->user();
        $user->delete();
        return response()->json(['message' => 'user deleted successfully'], 200);
    }

    public function index()
    {
        if(auth('api')->user()->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }
    public function show($phone)
    {
        $user = User::where('phone', $phone)->first();
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['user' => $user], 200);

    }
   


 

}