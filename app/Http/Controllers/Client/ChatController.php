<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Display the client's chat
    public function Chat(Request $request)
    {
        $user = $request->user();

        $conversation = ChatConversation::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark admin messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
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
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user) {
            $conversation = ChatConversation::firstOrCreate([
                'user_id' => $user->id,
            ]);

            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message' => $validated['message'],
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

    public function ChatMessages(Request $request)
    {
        $user = $request->user();

        $conversation = ChatConversation::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Mark messages from the admin as read.
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'Admin',
                    'message' => $message->message,
                    'created_at' => $message->created_at
                        ->format('d M Y, h:i A'),
                ];
            })->values(),
        ]);
    }
}
