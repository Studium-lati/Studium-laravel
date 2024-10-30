<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Ably\AblyRest;

class MessageController extends Controller
{
    private $ably;
    public function __construct()
    {
        $this->ably = new AblyRest(env('ABLY_API_KEY'));
    }
    function createChannelName($user1_id, $user2_id)
    {
        // Sort IDs to ensure a consistent channel name
        $userIds = [$user1_id, $user2_id];
        sort($userIds); // Sorts the array in ascending order

        // Create the channel name
        return 'chat-' . implode('-', $userIds);
    }

    public function sendMessage(Request $request)
    {

        $out = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required'
        ]);
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);


        // $message = Message::create([
        //     'sender_id' => auth()->id(),
        //     'receiver_id' => $request->receiver_id
        // ]);
        $channel = $this->ably->channel($this->createChannelName(auth()->id(), $request->receiver_id));
        $channel->publish('message', json_encode($message));
        return response()->json($message, 201);
    }

    public function showMessages($id)
    {
        
        $messages = auth()->user()->allMessages($id);
        return response()->json(['messages'=>$messages]);

    }
}
