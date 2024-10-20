<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploaderController extends Controller
{
    //
    public function uploadUserImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        // $request->file('image')->move(public_path('/avatars'), $imageName);
        $request->file('image')->storeAs('/avatars', $imageName, 'public');


        
       
        return response()->json(['success' => 'You have successfully uploaded an image',   'image_name' => $imageName], 200);
    }
public function uploadUserCover(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        // $request->file('image')->move(public_path('/covers'), $imageName);

        $request->file('image')->storeAs('/covers', $imageName, 'public');

       
        return response()->json(['success' => 'You have successfully uploaded an image',   'image_name' => $imageName], 200);
}




     public function uploadeventImage(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        // $request->file('image')->move(public_path('/events'), $imageName);
        $request->file('image')->storeAs('/events', $imageName, 'public');

       
        return response()->json(['success' => 'You have successfully uploaded an image',   'image_name' => $imageName], 200);
     }


    public function uploadStadiumImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
       $request->file('image')->storeAs('/stadiums', $imageName, 'public');
        // $img = $request->file(key: 'image')?->store('/stadiums', 'public');

        // $request->file('image')->move(public_path('/stadiums'), $imageName);
        return response()->json(['success' => 'You have successfully uploaded an image',   'image_name' => $imageName], 200);
    }


}
