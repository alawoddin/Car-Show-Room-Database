<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
     public function Chat(Request $request)
    {
        $conversation = ChatConversation::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark admin messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('client.chat.all_chat', compact(
            'conversation',
            'messages'
        ));
    }

    // Send a message to the admin
    public function ChatSend(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = ChatConversation::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        DB::transaction(function () use ($request, $conversation) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'message' => $request->message,
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
        ]);
    }
}
