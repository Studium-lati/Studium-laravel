<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;

class EventsController extends Controller
{
   function index(){
       $events = Events::where('status', 'active')->get();
        return response()->json(["data"=>$events]);
   }

    function show($id){
         $event = Events::find($id);
         return response()->json($event);
    }

    function create(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            'stadium_id' => 'required',
            'status' => 'nullable','default' => 'active','in' => ['active', 'inactive'],
            'image' => 'nullable'



        ]);

        if($request->user()->role != 'owner'){
            return response()->json(['message' => 'You are not allowed to create an event'], 403);
        }
        $event = new Events();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->stadium_id = $request->stadium_id;
        $event->user_id = $request->user()->id;
        $event->status = $request->status;
        $event->image = $request->image;
        $event->save();
        return response()->json($event);

    }

    function edit(Request $request, $id){
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'date' => '=nullable',
            'stadium_id' => 'nullable',
            'status' => 'nullable','in' => ['active', 'inactive'],
            'image' => 'nullable'

        ]);
        $event = Events::findOrFail($id);
        if($request->user()->role != 'owner' || $event->user_id != auth()->id()){
            return response()->json(['message' => 'You are not allowed to edit this event'], 403);
        }
        if($request->has('image')){   
                 $request->merge(['image' => "https://pigeon-wanted-wildcat.ngrok-free.app/storage/events/{$request->image}"]);
        }


        
        $event->update($request->all());    
       
        return response()->json($event);
    }

    public function destroy( $id){
        $event = Events::findOrFail($id);
        if(auth()->user()->role != 'owner' || $event->user_id != auth()->id()){
            return response()->json(['message' => 'You are not allowed to delete this event'], 403);
        }
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }


    public function changeStatus( $id){

       
        $event = Events::findOrFail($id);
        if(auth()->user()->role != 'owner' || $event->user_id != auth()->id()){
            return response()->json(['message' => 'You are not allowed to change the status of this event'], 403);
        }
        $event->status = $event->status == 'active' ? 'inactive' : 'active';
        $event->save();
        return response()->json($event);
    }
}
