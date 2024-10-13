<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Stadium;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index($id)
    {
       $stadium = Stadium::find($id);
         $feedbacks = $stadium->feedbacks;
        return response()->json($feedbacks);
    }

    public function create(Request $request)
    {
        $request->validate([
            'stadium_id' => 'required',
            'feedback' => 'required',
            'rating' => 'required','in => [1,2,3,4,5]',
        ]);
        $feedback = new Feedback();
        $feedback->user_id = auth()->id();
        $feedback->stadium_id = $request->stadium_id;
        $feedback->feedback = $request->feedback;
        $feedback->rating = $request->rating;
        $feedback->save();
        return response()->json($feedback);
    }

    public function averageRating($id)
    {
        $stadium = Stadium::find($id);
        $feedbacks = $stadium->feedbacks;
        $totalRating = 0;
        foreach ($feedbacks as $feedback) {
            $totalRating += $feedback->rating;
        }
        $averageRating = $totalRating / count($feedbacks);
        return response()->json($averageRating);
    }




}
