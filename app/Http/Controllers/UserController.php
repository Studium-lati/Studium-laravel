<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function create(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users|max:10| min:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'string','default:user','in:admin,user,owner',
            'avatar' => 'string',
            'cover' => 'string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $request->avatar,
            'cover' => $request->cover
        ]);

        return response()->json(['user' => $user, 'message' => 'user registered successfully'], 201
    );
//----------------------------------------------------------------------------



    }
    public Function login(Request $request){
        $request->validate([
            'phone' => 'required_without:email|string',
            'email' => 'required_without:phone|email',
            'password' => 'required|string'
        ]);
        $credentials =request(['phone','email', 'password']);
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
//----------------------------------------------------------------------------



    public function logout(){
        auth('api')->logout();
        return response()->json(['message'=>'User logged out successfully']);
    }

    //--------------------------------------------------------------------------------


    public function profile(){
        return response()->json(auth('api')->user());
    }
   //--------------------------------------------------------------------------------


    public function update(Request $request){
        $request->validate([
            'name' => 'string',
            'phone' => 'string|max:10| min:10',
            'email' => 'email',
            'role' => 'string|in:admin,user,owner',
            'avatar' => 'nullable',
            'cover' => 'nullable'

        ]);
        $user = auth('api')->user();
        if ($request->avatar!=null) {
            
        $request->merge(['avatar' => "https://pigeon-wanted-wildcat.ngrok-free.app/storage/avatars/{$request->avatar}"]);}
        if ($request->cover!=null) {
        $request->merge(['cover' => "https://pigeon-wanted-wildcat.ngrok-free.app/storage/covers/{$request->cover}"]);}

        $user->update($request->all());
        return response()->json(['user' => $user, 'message' => 'user updated successfully'], 200);
    }
    //--------------------------------------------------------------------------------


    public function updatePassword(Request $request){
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string'
        ]);
        $user = auth('api')->user();
        if(!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Old password is incorrect'], 400);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password updated successfully'], 200);
    }
//--------------------------------------------------------------------------------


    public function resetPassword(Request $request){
        $request->validate([
            'phone' => 'required|string|max:10| min:10',
            'email' => 'required|email',
            'new_password' => 'required|string'
        ]);
        $user = User::where('phone', $request->phone)->where('email', $request->email)->first();
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password reset successfully'], 200);
    }
//--------------------------------------------------------------------------------



    public function destroy(){
        $user = auth('api')->user();
        $user->delete();
        return response()->json(['message' => 'user deleted successfully'], 200);
    }
//--------------------------------------------------------------------------------



    public function index()
    {
        if(auth('api')->user()->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }
    
    
    //--------------------------------------------------------------------------------

    public function show($phone)
    {
        $user = User::where('phone', $phone)->first();
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['user' => $user], 200);

    }

    public function refresh(){
        
        $token = auth()->refresh( true, true);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
   



public function favorites(){
    $user = auth('api')->user();
    $favorites = $user->favorites;
    return response()->json(['favorites' => $favorites], 200);
}

public function showFaveStadiums(){
    $user = auth('api')->user();
    $favorites = $user->favorites;
    $stadiums = [];
    foreach ($favorites as $favorite) {
        $stadiums[] = $favorite->stadium;
    }
    return response()->json(['stadiums' => $stadiums], 200);
}
//--------------------------------------------------------------------------------
    public function addFavorite(Request $request){
        $request->validate([
            'stadium_id' => 'required|integer'
        ]);

        $user = auth('api')->user();
        $favorite = $user->favorites()->where('stadium_id', $request->stadium_id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Favorite removed successfully'], 200);
        }

      Favorite::create([
            'user_id' => $user->id,
            'stadium_id' => $request->stadium_id
        ]);

        return response()->json(['favorite' => $favorite, 'message' => 'Favorite added successfully'], 201);
        
    }
   
   

 

}
