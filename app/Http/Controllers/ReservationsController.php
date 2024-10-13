<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Models\Stadium;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $user = $request->user();
    $reservations = Reservations::query();

    if ($user->role == 'admin') {
        $reservations = $reservations->get();
    } elseif ($user->role == 'owner') {
        $reservations = $reservations->whereHas('stadium', function ($query) use ($user) {
        $query->where('user_id', $user->id);
        })->get();
    } else {
        $reservations = $reservations->where('user_id', $user->id)->get();
    }

    return response()->json($reservations);
        
    }





    public function reservationStatus(Request $request)
    {
        if($request->has('status')){
            $reservations = Reservations::where('status', $request->status)->get();
            return response()->json($reservations);
        }
        return response()->json(['message' => 'status not found'], 404);
        
    }





    public function reserveStadium (Request $request)
    {
        $request->validate([
            'stadium_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'duration' => 'required',
            'deposit' => 'required'
        ]);

        $user = $request->user();
        $stadium = Stadium::find($request->stadium_id);

        if (!$stadium) {
            return response()->json(['message' => 'stadium not found'], 404);
        }

        $reservation = new Reservations();
        $reservation->stadium_id = $request->stadium_id;
        $reservation->user_id = $user->id;
        $reservation->date = $request->date;
        $reservation->time = $request->time;
        $reservation->duration = $request->duration;
        $reservation->price = $stadium->price_per_hour * $request->duration;
        $reservation->deposit = $request->deposit;
        $reservation->save();

        return response()->json($reservation);
        
    }





    public function cancelReservation( $id)
    {
        $user = auth()->user();
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'reservation not found'], 404);
        }

        if ($user->role == 'onwer'|| $user->role=='admin' || $user->id == $reservation->user_id) {
            $reservation->delete();
            return response()->json(['message' => 'reservation canceled']);
        }

        return response()->json(['message' => 'unauthorized'], 401);
        
    }



    public function changeStatus(Request $request)
{
    $request->validate([
        'status' => 'required',
        'id' => 'required'
    ]);

    $user = auth()->user();
    $reservation = Reservations::find($request->id);

    if (!$reservation) {
        return response()->json(['message' => 'reservation not found'], 404);
    }

    if ($user->role == 'admin' || $user->role == 'owner') {
        $reservation->status = $request->status;
        $reservation->save();
        return response()->json($reservation);
    }

    return response()->json(['message' => 'unauthorized'], 401);
}

public function viewReservations(){
    $reservations = auth()->user()->reservations;
    return response()->json($reservations);

}



}
