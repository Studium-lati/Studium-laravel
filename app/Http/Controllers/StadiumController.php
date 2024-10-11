<?php

namespace App\Http\Controllers;

use App\Models\Stadium;
use Illuminate\Http\Request;

class StadiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stauims = Stadium::where('status', 'open')->get();
        return response()->json($stauims, 200);

        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'price_per_hour' => 'required',
            'capacity' => 'numeric','default:12',
            'image' => 'nullable',
            'description' => 'nullable',
            'status' => 'in:open,close','default:open',
            'user_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::where('id', $value)->where('role', 'owner')->first();
                    if (!$user) {
                        $fail('The selected user is not an owner.');
                    }
                },
            ]
        ]);
        if(auth()->user()->role == 'admin'){
            Stadium::create($request->all());
            return response()->json(['message' => 'Stadium created successfully'], 201);

        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        if(auth()->user()->role == 'owner'){
            $stadiums = auth()->user()->stadiums()->get();
            return response()->json($stadiums, 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
       

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,  $stadiumId)
    {
        $request->validate([
            'name' => 'nullable',
            'location' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'price_per_hour' => 'nullable',
            'capacity' => 'numeric','default:12','nullable',
            'image' => 'nullable',
            'description' => 'nullable',
            'status' => 'in:open,close','default:open',
            'user_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::where('id', $value)->where('role', 'owner')->first();
                    if (!$user) {
                        $fail('The selected user is not an owner.');
                    }
                },
            ]
        ]);
        $stadium = Stadium::find($stadiumId);
         
        if(auth()->user()->id == $stadium->user_id){
            $stadium->update($request->all());
            return response()->json(['message' => 'Stadium updated successfully'], 200);
    }
    return response()->json(['message' => 'Unauthorized'], 401);
      
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stadium $stadium)
    {
        //
    }

    public function showStadium(Request $request)

    {
        if($request->has('name')){
            $stadium = Stadium::where('name', 'like', '%'.$request->name.'%')->get();
            return response()->json($stadium, 200);
        }
        return response()->json(['message' => 'Stadium not found'], 404);
       
    }

    public function changeStatus( $stadium)
    {
        
        $stadium = Stadium::find($stadium);
        if(auth()->user()->id== Stadium::where('id', $stadium->id)->first()->user_id){
            $stadium->update(['status' => $stadium->status == 'open' ? 'close' : 'open']);
            return response()->json(['message' => 'Stadium status updated successfully'], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $stadium)
    {
    if(auth()->user()->role == 'admin'){
        $stadium = Stadium::find($stadium)->delete();
        
        return response()->json(['message' => 'Stadium deleted successfully'], 200);
    }
    return response()->json(['message' => 'Unauthorized'], 401);
    }
}
