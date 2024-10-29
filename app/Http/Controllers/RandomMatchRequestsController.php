<?php

namespace App\Http\Controllers;

use App\Models\MatchLog;
use App\Models\RandomMatchRequests;
use Illuminate\Http\Request;

class RandomMatchRequestsController extends Controller
{
    
    public function requestRandomMatch(Request $request)
    {
        $request->validate([
            'preferred_time' => 'nullable|date_format:H:i',
            'preferred_date' => 'nullable|date',
            'is_available_all_days' => 'boolean',
        ]);
        $userId = auth()->id();
        $preferredTime = $request->input('preferred_time');
        $preferredDate = $request->input('preferred_date');
        $isAvailableAllTime = $request->input('is_available_all_days');

        // Create a new random match request
        RandomMatchRequests::create([
            'user_id' => $userId,
            'preferred_date' => $preferredDate,
            'preferred_time' => $preferredTime,
            'is_available_all_days' => $isAvailableAllTime,
            'status' => 'pending',
        ]);

        return $this->findMatchForUser($userId);
    }

    public function findMatchForUser($userId)
    {
        $userRequest = RandomMatchRequests::where('user_id', $userId)
                            ->where('status', 'pending')
                            ->first();

        if (!$userRequest) {
            return response()->json("No pending match request found for this user.",202);
        }

        // Find a match with another pending request that meets the criteria
        $match = RandomMatchRequests::where('status', 'pending')
                    ->where('user_id', '!=', $userId)
                    ->where(function ($query) use ($userRequest) {
                        $query->where('preferred_date', $userRequest->preferred_date)
                              ->where('preferred_time', $userRequest->preferred_time)
                              ->orWhere('is_available_all_days', true)
                              ->orWhere('preferred_time', '>=', now());
                    })
                    ->first();

        if ($match) {
            // Update the status of both requests to 'matched'
            $userRequest->status = 'matched';
            $userRequest->save();

            $match->status = 'matched';
            $match->save();

            // Log the match in the 'match_logs' table
            $this->logMatch($userRequest->user_id, $match->user_id);
           return response()->json(
        [
            'message' => 'Match found!',
            'user1' => $userRequest->user,
            'user2' => $match->user,
        ]
           );
        }

        return response()->json("No match found at the moment.",202);
    }

    // Log the match into the match_logs table
    public function logMatch($user1Id, $user2Id)
    {
        MatchLog::create([
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
            'match_time' => now(),
        ]);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:random_match_requests,id',
        ]);

        $requestId = $request->input('request_id');
        $userId = auth()->id();

        $userRequest = RandomMatchRequests::where('id', $requestId)
                            ->where('user_id', $userId)
                            ->where('status', 'pending')
                            ->first();

        if (!$userRequest) {
            return response()->json("No pending match request found for this user.");
        }

        $userRequest->status = 'canceld';
        $userRequest->save();

        return response()->json("Match request cancelled successfully.");
    }

    public function checkMatched(){
        $userId = auth()->id();
        $userRequest = RandomMatchRequests::where('user_id', $userId)
                            
                            ->orderBy('id', 'desc')->firstOrFail();

        if ($userRequest->status != 'matched') {
            return response()->json(["No matched request found for this user."],202);
        }

        return response()->json(["Matched request found for this user." ,$userRequest] ,200);

    }

    
}
